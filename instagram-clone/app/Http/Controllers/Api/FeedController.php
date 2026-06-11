<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;

class FeedController extends Controller
{
    public function index()
    {
        $followingIds = auth()->user()->following->pluck('id');

        $posts = Post::whereIn('user_id', $followingIds)
            ->with('user')
            ->latest()
            ->get();

        return response()->json($posts);
    }
}
