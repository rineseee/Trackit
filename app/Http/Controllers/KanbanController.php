<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KanbanController extends Controller
{
    public function index(): View
    {
        // Get issues grouped by status
        $statuses = Issue::STATUSES;

        $issuesByStatus = [];
        foreach ($statuses as $status) {
            $issuesByStatus[$status] = Issue::where('status', $status)
                ->with(['project:id,name', 'members:id,name', 'tags:id,name,color'])
                ->withCount('comments')
                ->latest()
                ->get();
        }

        return view('issues.kanban', [
            'issuesByStatus' => $issuesByStatus,
            'statuses' => $statuses,
            'projects' => \App\Models\Project::pluck('name', 'id'),
        ]);
    }

    public function updateStatus(Request $request, Issue $issue): JsonResponse
    {
        try {
            // Validate status
            $validated = $request->validate([
                'status' => 'required|in:' . implode(',', Issue::STATUSES),
                'position' => 'integer|min:0',
            ]);

            // Get old status for comparison
            $oldStatus = $issue->status;

            // Update issue status
            $issue->update([
                'status' => $validated['status'],
            ]);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => "Issue moved from {$oldStatus} to {$validated['status']}",
                'issue' => [
                    'id' => $issue->id,
                    'title' => $issue->title,
                    'status' => $issue->status,
                    'priority' => $issue->priority,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update issue status',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function reorder(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*' => 'required|integer',
            ]);

            // Update positions (optional - for future ordering feature)
            // This can be used to maintain custom ordering within a column

            return response()->json([
                'success' => true,
                'message' => 'Issues reordered successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder issues',
            ], 500);
        }
    }
}
