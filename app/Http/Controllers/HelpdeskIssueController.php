<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueComment;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HelpdeskIssueController extends Controller
{
    // List issues with filters
    public function index(Request $request): View
    {
        $query = Issue::with(['project', 'assignedTo', 'tags'])
            ->withCount('comments');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority'));
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->integer('project'));
        }

        if ($request->filled('assigned')) {
            $query->where('assigned_to_id', $request->integer('assigned'));
        }

        // Apply search
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $issues = $query->latest()->paginate(25);

        return view('issues.helpdesk-index', [
            'issues' => $issues,
            'projects' => Project::all(),
            'users' => User::all(),
        ]);
    }

    // Show issue details
    public function show(Issue $issue): View
    {
        $issue->load(['comments.user', 'tags', 'assignedTo', 'project']);

        return view('issues.helpdesk-show', [
            'issue' => $issue,
            'allTags' => Tag::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    // Update issue (AJAX)
    public function update(Request $request, Issue $issue): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'nullable|in:open,in_progress,closed',
            'priority' => 'nullable|in:low,medium,high',
            'assigned_to_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $issue->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Issue updated successfully',
        ]);
    }

    // Delete issue
    public function destroy(Issue $issue): JsonResponse
    {
        $issue->delete();

        return response()->json([
            'success' => true,
            'message' => 'Issue deleted successfully',
        ]);
    }

    // Add comment (AJAX)
    public function addComment(Request $request, Issue $issue): JsonResponse
    {
        $validated = $request->validate([
            'body' => 'required|string|min:1|max:1000',
        ]);

        $comment = $issue->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'user' => [
                    'id' => auth()->user()->id,
                    'name' => auth()->user()->name,
                ],
                'created_at' => $comment->created_at->diffForHumans(),
            ],
        ]);
    }

    // Delete comment (AJAX)
    public function deleteComment(Issue $issue, IssueComment $comment): JsonResponse
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted',
        ]);
    }

    // Update tags (AJAX)
    public function updateTags(Request $request, Issue $issue): JsonResponse
    {
        $validated = $request->validate([
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $issue->tags()->sync($validated['tags'] ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Tags updated',
        ]);
    }

    // Remove tag (AJAX)
    public function removeTag(Issue $issue, Tag $tag): JsonResponse
    {
        $issue->tags()->detach($tag);

        return response()->json([
            'success' => true,
            'message' => 'Tag removed',
        ]);
    }
}
