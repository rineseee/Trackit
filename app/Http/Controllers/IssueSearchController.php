<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->query('q', '');
        $limit = $request->query('limit', 10);

        // Validate query length
        if (strlen(trim($query)) === 0) {
            return response()->json([
                'success' => true,
                'data' => [],
                'count' => 0,
                'message' => 'Enter a search term',
            ]);
        }

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
                'count' => 0,
                'message' => 'Search term must be at least 2 characters',
            ]);
        }

        try {
            // Search in title and description
            $issues = Issue::query()
                ->with(['project:id,name', 'members:id,name', 'tags:id,name,color'])
                ->where(function ($q) use ($query) {
                    $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($query) . '%'])
                      ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($query) . '%']);
                })
                ->withCount('comments')
                ->limit($limit)
                ->latest()
                ->get();

            // Format response
            $formattedIssues = $issues->map(fn ($issue) => [
                'id' => $issue->id,
                'title' => $issue->title,
                'description' => $issue->description ? substr($issue->description, 0, 100) . '...' : 'No description',
                'status' => $issue->status,
                'priority' => $issue->priority,
                'project' => [
                    'id' => $issue->project->id,
                    'name' => $issue->project->name,
                ],
                'members' => $issue->members->map(fn ($member) => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'initials' => $this->getInitials($member->name),
                ])->toArray(),
                'tags' => $issue->tags->map(fn ($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'color' => $tag->color,
                ])->toArray(),
                'comments_count' => $issue->comments_count,
                'url' => route('issues.show', $issue),
            ])->toArray();

            return response()->json([
                'success' => true,
                'data' => $formattedIssues,
                'count' => count($formattedIssues),
                'total' => Issue::whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($query) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($query) . '%'])
                    ->count(),
                'message' => count($formattedIssues) > 0 ? 'Found ' . count($formattedIssues) . ' results' : 'No results found',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'An error occurred during search',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function getInitials(string $name): string
    {
        return collect(explode(' ', $name))
            ->filter()
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->implode('');
    }
}
