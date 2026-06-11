<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function index()
    {
        $followingIds = auth()->user()->following->pluck('id');

        $stories = Story::whereIn('user_id', $followingIds)
            ->orWhere('user_id', auth()->id())
            ->with('user')
            ->latest('created_at')
            ->get();

        return response()->json($stories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        $path = $request->file('image')->store('stories', 'public');

        $story = Story::create([
            'user_id' => auth()->id(),
            'image_path' => $path,
        ]);

        return response()->json($story, 201);
    }

    public function show(Story $story)
    {
        return response()->json($story->load('user'));
    }
}
