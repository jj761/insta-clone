<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,mp4,mov',
            'caption' => 'nullable|string',
        ]);

        $path = $request->file('image')->store('posts', 'public');

        $post = Post::create([
            'user_id' => auth()->id(),
            'image_path' => $path,
            'caption' => $request->input('caption'),
        ]);

        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        return response()->json($post->load('user'));
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
