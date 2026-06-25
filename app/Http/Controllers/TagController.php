<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::query()
            ->withCount('issues')
            ->orderBy('name')
            ->paginate(12);

        return view('tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('tags.create', [
            'tag' => new Tag(),
        ]);
    }

    public function store(StoreTagRequest $request): RedirectResponse|JsonResponse
    {
        $tag = Tag::create($request->validated());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag created successfully.',
                'tag' => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'color' => $tag->color,
                ],
            ], 201);
        }

        return redirect()
            ->route('tags.index')
            ->with('status', 'Tag created successfully.');
    }
}
