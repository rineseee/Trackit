<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueCommentController extends Controller
{
    public function index(Request $request, Issue $issue): JsonResponse
    {
        $comments = $issue->comments()
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return response()->json([
            'data' => $comments->getCollection()->map(fn ($comment): array => [
                'id' => $comment->id,
                'author_name' => $comment->author_name,
                'body' => $comment->body,
                'created_at' => $comment->created_at?->toIso8601String(),
                'created_at_human' => $comment->created_at?->diffForHumans(),
            ])->values(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
                'next_page_url' => $comments->nextPageUrl(),
                'prev_page_url' => $comments->previousPageUrl(),
            ],
        ]);
    }

    public function store(StoreCommentRequest $request, Issue $issue): JsonResponse
    {
        $comment = $issue->comments()->create($request->validated());

        return response()->json([
            'message' => 'Comment added.',
            'comment' => [
                'id' => $comment->id,
                'author_name' => $comment->author_name,
                'body' => $comment->body,
                'created_at' => $comment->created_at?->toIso8601String(),
                'created_at_human' => $comment->created_at?->diffForHumans(),
            ],
        ], 201);
    }
}
