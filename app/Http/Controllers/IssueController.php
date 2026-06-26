<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    protected NotificationService $notification;

    public function __construct(NotificationService $notification)
    {
        $this->notification = $notification;
    }
    public function index(Request $request): View|JsonResponse
    {
        $issues = Issue::query()
            ->with(['project', 'tags', 'members'])
            ->withCount('comments')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->string('priority')))
            ->when($request->filled('tag'), fn ($query) => $query->whereHas('tags', fn ($tagQuery) => $tagQuery->whereKey($request->integer('tag'))))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim($request->string('search')->toString());

                if ($search === '') {
                    return;
                }

                $query->where(function ($nested) use ($search): void {
                    $nested->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('issues._list', compact('issues'))->render(),
                'pagination' => view('issues._pagination', compact('issues'))->render(),
                'total' => $issues->total(),
            ]);
        }

        return view('issues.index', [
            'issues' => $issues,
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
            'tags' => Tag::query()->orderBy('name')->get(['id', 'name', 'color']),
        ]);
    }

    public function create(): View
    {
        return view('issues.create', [
            'issue' => new Issue(),
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
            'tags' => Tag::query()->orderBy('name')->get(['id', 'name', 'color']),
        ]);
    }

    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $tagIds = $data['tags'] ?? [];
        unset($data['tags']);

        $issue = Issue::create($data);
        $issue->tags()->sync($tagIds);

        $this->notification->success(
            "Issue \"{$issue->title}\" has been created successfully.",
            'Issue Created'
        );

        return redirect()->route('issues.show', $issue);
    }

    public function show(Issue $issue): View
    {
        $issue->load([
            'project',
            'tags',
            'members',
            'comments' => fn ($query) => $query->oldest(),
        ]);

        return view('issues.show', [
            'issue' => $issue,
            'allTags' => Tag::query()->orderBy('name')->get(['id', 'name', 'color']),
            'users' => User::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
        ]);
    }

    public function edit(Issue $issue): View
    {
        $issue->load(['tags']);

        return view('issues.edit', [
            'issue' => $issue,
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
            'tags' => Tag::query()->orderBy('name')->get(['id', 'name', 'color']),
        ]);
    }

    public function update(UpdateIssueRequest $request, Issue $issue): RedirectResponse
    {
        $data = $request->validated();
        $tagIds = $data['tags'] ?? [];
        unset($data['tags']);

        $issue->update($data);
        $issue->tags()->sync($tagIds);

        $this->notification->success(
            "Issue \"{$issue->title}\" has been updated successfully.",
            'Issue Updated'
        );

        return redirect()->route('issues.show', $issue);
    }

    public function destroy(Issue $issue): RedirectResponse
    {
        $issueName = $issue->title;
        $issue->delete();

        $this->notification->success(
            "Issue \"{$issueName}\" has been deleted successfully.",
            'Issue Deleted'
        );

        return redirect()->route('issues.index');
    }

    public function kanban(): View
    {
        $issues = Issue::query()
            ->with(['project', 'tags', 'members'])
            ->get()
            ->groupBy('status');

        return view('issues.kanban', compact('issues'));
    }
}
