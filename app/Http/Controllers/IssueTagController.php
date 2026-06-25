<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class IssueTagController extends Controller
{
    public function store(Issue $issue, Tag $tag): JsonResponse
    {
        $issue->tags()->syncWithoutDetaching([$tag->id]);

        return response()->json([
            'message' => 'Tag attached.',
            'tags' => $this->serializedTags($issue->fresh('tags')->tags),
        ]);
    }

    public function destroy(Issue $issue, Tag $tag): JsonResponse
    {
        $issue->tags()->detach($tag->id);

        return response()->json([
            'message' => 'Tag detached.',
            'tags' => $this->serializedTags($issue->fresh('tags')->tags),
        ]);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\Tag>  $tags
     */
    private function serializedTags($tags): array
    {
        return $tags->map(fn (Tag $tag): array => [
            'id' => $tag->id,
            'name' => $tag->name,
            'color' => $tag->color,
        ])->values()->all();
    }
}
