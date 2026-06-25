<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndIssueTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_is_logged_in(): void
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/projects');
        $this->assertAuthenticated();
    }

    public function test_issue_show_page_loads_with_members_and_tags(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::create([
            'owner_id' => $owner->id,
            'name' => 'Website Redesign',
            'description' => 'Refresh the marketing site.',
            'start_date' => now()->toDateString(),
            'deadline' => now()->addWeek()->toDateString(),
        ]);
        $tag = Tag::factory()->create();
        $issue = Issue::create([
            'project_id' => $project->id,
            'title' => 'Homepage hero spacing',
            'description' => 'Tighten the hero layout.',
            'status' => 'open',
            'priority' => 'medium',
            'due_date' => now()->addDays(5)->toDateString(),
        ]);

        $issue->tags()->attach($tag->id);
        $issue->members()->attach($member->id);

        $this->get("/issues/{$issue->id}")->assertOk();
    }

    public function test_issue_index_ajax_returns_fragments(): void
    {
        $owner = User::factory()->create();
        $project = Project::create([
            'owner_id' => $owner->id,
            'name' => 'Mobile App',
            'description' => 'Issue tracking test project.',
            'start_date' => now()->toDateString(),
            'deadline' => now()->addWeek()->toDateString(),
        ]);

        Issue::create([
            'project_id' => $project->id,
            'title' => 'Crash on submit',
            'description' => 'Button issue',
            'status' => 'open',
            'priority' => 'high',
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $response = $this
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->get('/issues?search=Crash');

        $response->assertOk();
        $response->assertJsonStructure(['html', 'pagination', 'total']);
        $response->assertJsonPath('total', 1);
    }

    public function test_issue_members_can_be_attached_and_detached_with_ajax(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::create([
            'owner_id' => $owner->id,
            'name' => 'Assignments',
            'description' => 'Member assignment test.',
            'start_date' => now()->toDateString(),
            'deadline' => now()->addWeek()->toDateString(),
        ]);
        $issue = Issue::create([
            'project_id' => $project->id,
            'title' => 'Assign reviewer',
            'description' => 'Attach a user via AJAX.',
            'status' => 'open',
            'priority' => 'medium',
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $attachResponse = $this
            ->actingAs($owner)
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->postJson("/issues/{$issue->id}/members/{$member->id}");

        $attachResponse->assertOk();
        $attachResponse->assertJsonPath('members.0.id', $member->id);
        $this->assertDatabaseHas('issue_user', [
            'issue_id' => $issue->id,
            'user_id' => $member->id,
        ]);

        $detachResponse = $this
            ->actingAs($owner)
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->deleteJson("/issues/{$issue->id}/members/{$member->id}");

        $detachResponse->assertOk();
        $detachResponse->assertJsonPath('members', []);
        $this->assertDatabaseMissing('issue_user', [
            'issue_id' => $issue->id,
            'user_id' => $member->id,
        ]);
    }

    public function test_only_project_owner_can_edit_or_delete_a_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::create([
            'owner_id' => $owner->id,
            'name' => 'Private Roadmap',
            'description' => 'Owner-only project.',
            'start_date' => now()->toDateString(),
            'deadline' => now()->addWeek()->toDateString(),
        ]);

        $this->actingAs($owner)
            ->get("/projects/{$project->id}/edit")
            ->assertOk();

        $this->actingAs($otherUser)
            ->get("/projects/{$project->id}/edit")
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete("/projects/{$project->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'owner_id' => $owner->id,
        ]);
    }

    public function test_guests_cannot_create_issues_or_mutate_issue_data(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::create([
            'owner_id' => $owner->id,
            'name' => 'Guest Locked Project',
            'description' => 'Guests can read but not write.',
            'start_date' => now()->toDateString(),
            'deadline' => now()->addWeek()->toDateString(),
        ]);
        $issue = Issue::create([
            'project_id' => $project->id,
            'title' => 'Guest cannot change me',
            'description' => 'Protected issue.',
            'status' => 'open',
            'priority' => 'medium',
            'due_date' => now()->addDays(2)->toDateString(),
        ]);
        $tag = Tag::factory()->create();

        $this->get('/issues/create')->assertRedirect('/login');

        $this->post('/issues', [
            'project_id' => $project->id,
            'title' => 'Unauthorized issue',
            'description' => 'Should not be created.',
            'status' => 'open',
            'priority' => 'medium',
        ])->assertRedirect('/login');

        $this->postJson("/issues/{$issue->id}/tags/{$tag->id}")->assertRedirect('/login');
        $this->postJson("/issues/{$issue->id}/members/{$member->id}")->assertRedirect('/login');
        $this->postJson("/issues/{$issue->id}/comments", [
            'author_name' => 'Guest',
            'body' => 'Not allowed.',
        ])->assertRedirect('/login');

        $this->assertDatabaseMissing('issues', [
            'title' => 'Unauthorized issue',
        ]);
        $this->assertDatabaseMissing('issue_tag', [
            'issue_id' => $issue->id,
            'tag_id' => $tag->id,
        ]);
        $this->assertDatabaseMissing('issue_user', [
            'issue_id' => $issue->id,
            'user_id' => $member->id,
        ]);
    }
}
