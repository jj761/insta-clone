<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Story;

class FeedController extends Controller
{
    public function index()
    {
        $followingIds = auth()->user()->following->pluck('id');

        $posts = Post::whereIn('user_id', $followingIds)
            ->latest()
            ->get();

        $stories = Story::whereIn('user_id', $followingIds)
            ->orWhere('user_id', auth()->id())
            ->latest('created_at')
            ->get();

        return view('feed.index', compact('posts', 'stories'));
    }
}
