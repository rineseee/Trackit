<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Statistics
        $total = Issue::count();
        $statuses = ['open','in_progress','pending','resolved','closed','canceled'];
        $statusCounts = [];
        foreach ($statuses as $s) {
            $statusCounts[$s] = Issue::where('status', $s)->count();
        }

        // Priorities
        $priorities = ['low','medium','high'];
        $priorityCounts = [];
        foreach ($priorities as $p) {
            $priorityCounts[$p] = Issue::where('priority', $p)->count();
        }

        // Filters data
        $projects = Project::orderBy('name')->get(['id','name']);
        $tags = Tag::orderBy('name')->get(['id','name', 'color']);
        $assignees = User::orderBy('name')->get(['id','name']);

        $recentIssues = Issue::with(['project', 'members', 'tags'])->latest()->take(6)->get();
        $recentProjects = Project::withCount('issues')->orderBy('updated_at','desc')->take(6)->get();

        $today = now()->startOfDay();
        $openIssues = $statusCounts['open'] ?? 0;
        $inProgressIssues = $statusCounts['in_progress'] ?? 0;
        $closedIssues = $statusCounts['closed'] ?? 0;
        $overdueIssues = Issue::whereNotNull('due_date')
            ->whereDate('due_date', '<', $today)
            ->where('status', '!=', 'closed')
            ->count();

        $recentComments = Comment::with(['issue.project'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingDeadlines = Issue::with('project')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', $today)
            ->orderBy('due_date')
            ->take(6)
            ->get();

        $byProject = Project::withCount('issues')->get();
        $issuesByProject = [];
        foreach ($byProject as $project) {
            $issuesByProject[$project->name] = $project->issues_count;
        }

        return view('dashboard.index', [
            'issuesByProject' => $issuesByProject,
            'total' => $total,
            'totalIssues' => $total,
            'totalProjects' => Project::count(),
            'openIssues' => $openIssues,
            'inProgressIssues' => $inProgressIssues,
            'closedIssues' => $closedIssues,
            'overdueIssues' => $overdueIssues,
            'openRate' => $total > 0 ? round(($openIssues / $total) * 100) : 0,
            'progressRate' => $total > 0 ? round(($inProgressIssues / $total) * 100) : 0,
            'closedRate' => $total > 0 ? round(($closedIssues / $total) * 100) : 0,
            'overdueRate' => $total > 0 ? round(($overdueIssues / $total) * 100) : 0,
            'statusCounts' => $statusCounts,
            'issuesByStatus' => $statusCounts,
            'priorityCounts' => $priorityCounts,
            'issuesByPriority' => $priorityCounts,
            'projects' => $projects,
            'tags' => $tags,
            'assignees' => $assignees,
            'statuses' => $statuses,
            'priorities' => $priorities,
            'recentIssues' => $recentIssues,
            'recentProjects' => $recentProjects,
            'recentComments' => $recentComments,
            'upcomingDeadlines' => $upcomingDeadlines,
        ]);
    }

    // Server-side DataTables endpoint
    public function issuesData(Request $request)
    {
        $columns = [
            'id',
            'projects.name',
            'title',
            'requester',
            'priority',
            'status',
            'due_date',
            'tags',
            'created_at',
            'updated_at'
        ];

        $query = Issue::query()->with(['project','tags','members']);

        // Filters
        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('tag')) {
            $tagId = (int) $request->tag;
            $query->whereHas('tags', function ($q) use ($tagId) { $q->where('tags.id', $tagId); });
        }
        if ($request->filled('assignee')) {
            $assigneeId = (int) $request->assignee;
            $query->whereHas('members', function ($q) use ($assigneeId) { $q->where('users.id', $assigneeId); });
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Global search
        $search = $request->input('search.value');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $recordsTotal = Issue::count();
        $recordsFiltered = $query->count();

        // Ordering
        $orderColIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $orderColumn = $columns[$orderColIndex] ?? 'id';

        // If ordering by related column handle separately
        if ($orderColumn === 'projects.name') {
            $query = $query->join('projects','projects.id','issues.project_id')->orderBy('projects.name', $orderDir)->select('issues.*');
        } else {
            $query = $query->orderBy($orderColumn, $orderDir);
        }

        // Pagination
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $rows = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $issue) {
            $project = $issue->project?->name ?? '-';
            $assignee = $issue->members->first()?->name ?? '-';

            // Status badge
            $statusMap = [
                'open' => 'badge-danger',
                'in_progress' => 'badge-warning',
                'pending' => 'badge-primary',
                'resolved' => 'badge-success',
                'closed' => 'badge-secondary',
                'canceled' => 'badge-danger'
            ];
            $statusLabel = ucfirst(str_replace('_',' ', $issue->status));
            $statusBadge = '<span class="status-badge '.($statusMap[$issue->status] ?? 'badge-secondary').'">'.e($statusLabel).'</span>';

            // Priority badge
            $priorityMap = [
                'low' => 'priority-low',
                'medium' => 'priority-medium',
                'high' => 'priority-high'
            ];
            $priorityLabel = ucfirst($issue->priority ?? '');
            $priorityBadge = '<span class="priority-badge '.($priorityMap[$issue->priority] ?? 'priority-low').'">'.e($priorityLabel).'</span>';

            // Tags
            $tagsHtml = '';
            foreach ($issue->tags as $tag) {
                $tagsHtml .= '<span class="tag-pill">'.e($tag->name).'</span> ';
            }

            $actions = '<div class="d-flex gap-2">'
                .'<a href="'.route('issues.show', $issue->id).'" class="btn btn-sm btn-light" title="View"><i class="fa fa-eye"></i></a>'
                .'<a href="'.route('issues.edit', $issue->id).'" class="btn btn-sm btn-light" title="Edit"><i class="fa fa-pen"></i></a>'
                .'<form method="POST" action="'.route('issues.destroy', $issue->id).'" onsubmit="return confirm(\'Delete this issue?\');">'
                .csrf_field().method_field('DELETE').'<button class="btn btn-sm btn-light text-danger" title="Delete"><i class="fa fa-trash"></i></button></form></div>';

            $data[] = [
                'id' => '#'.$issue->id,
                'project' => $project,
                'title' => e($issue->title),
                'assignee' => e($assignee),
                'priority' => $priorityBadge,
                'status' => $statusBadge,
                'due_date' => $issue->due_date?->format('Y-m-d') ?? '-',
                'tags' => $tagsHtml,
                'created_at' => $issue->created_at->format('Y-m-d H:i'),
                'updated_at' => $issue->updated_at->format('Y-m-d H:i'),
                'actions' => $actions,
            ];
        }

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    // Inline CRUD for issues via AJAX (used by dashboard modals)
    public function showIssue(Issue $issue)
    {
        return response()->json($issue->load('project','tags','members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,pending,resolved,closed,canceled',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|array',
            'assignee' => 'nullable|exists:users,id',
        ]);

        $issue = Issue::create([
            'project_id' => $validated['project_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        if (!empty($validated['tags'])) {
            $issue->tags()->sync($validated['tags']);
        }
        if (!empty($validated['assignee'])) {
            $issue->members()->sync([$validated['assignee']]);
        }

        return response()->json(['success' => true, 'issue' => $issue->load('project','tags','members')]);
    }

    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,pending,resolved,closed,canceled',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|array',
            'assignee' => 'nullable|exists:users,id',
        ]);

        $issue->update([
            'project_id' => $validated['project_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        if (isset($validated['tags'])) {
            $issue->tags()->sync($validated['tags']);
        }
        if (isset($validated['assignee'])) {
            $issue->members()->sync([$validated['assignee']]);
        }

        return response()->json(['success' => true, 'issue' => $issue->load('project','tags','members')]);
    }

    public function destroy(Issue $issue)
    {
        $issue->delete();
        return response()->json(['success' => true]);
    }
}
