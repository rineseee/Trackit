<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectController extends Controller
{
    protected NotificationService $notification;

    public function __construct(NotificationService $notification)
    {
        $this->notification = $notification;
    }
    public function index(): View
    {
        $projects = Project::query()
            ->with('owner:id,name')
            ->withCount([
                'issues',
                'issues as closed_issues_count' => fn ($query) => $query->where('status', 'closed'),
            ])
            ->latest()
            ->paginate(8);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create', [
            'project' => new Project(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = Project::create([
            ...$request->validated(),
            'owner_id' => $request->user()->id,
        ]);

        $this->notification->success(
            "Project \"{$project->name}\" has been created successfully.",
            'Project Created'
        );

        return redirect()->route('projects.show', $project);
    }

    public function show(Project $project): View
    {
        $project->load([
            'owner:id,name,email',
            'issues' => fn ($query) => $query
                ->with(['tags', 'members'])
                ->withCount('comments')
                ->latest(),
        ]);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        Gate::authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $project->update($request->validated());

        $this->notification->success(
            "Project \"{$project->name}\" has been updated successfully.",
            'Project Updated'
        );

        return redirect()->route('projects.show', $project);
    }

    public function destroy(Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        $projectName = $project->name;
        $project->delete();

        $this->notification->success(
            "Project \"{$projectName}\" has been deleted successfully.",
            'Project Deleted'
        );

        return redirect()->route('projects.index');
    }
}
