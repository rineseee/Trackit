<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class IssueMemberController extends Controller
{
    public function store(Issue $issue, User $user): JsonResponse
    {
        $issue->members()->syncWithoutDetaching([$user->id]);

        return response()->json([
            'message' => 'Member assigned.',
            'members' => $this->serializedMembers($issue->fresh('members')->members),
        ]);
    }

    public function destroy(Issue $issue, User $user): JsonResponse
    {
        $issue->members()->detach($user->id);

        return response()->json([
            'message' => 'Member removed.',
            'members' => $this->serializedMembers($issue->fresh('members')->members),
        ]);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\User>  $members
     */
    private function serializedMembers($members): array
    {
        return $members->map(fn (User $user): array => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'initials' => collect(explode(' ', $user->name))
                ->filter()
                ->map(fn (string $part): string => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode(''),
        ])->values()->all();
    }
}
