<?php

namespace App\Http\Controllers;

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

        Post::create([
            'user_id' => auth()->id(),
            'image_path' => $path,
            'caption' => $request->input('caption'),
        ]);

        return redirect('/dashboard');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        $post->delete();

        return redirect('/dashboard');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }
}
