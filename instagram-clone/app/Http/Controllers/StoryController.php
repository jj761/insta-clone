<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        $path = $request->file('image')->store('stories', 'public');

        Story::create([
            'user_id' => auth()->id(),
            'image_path' => $path,
        ]);

        return redirect('/dashboard');
    }

    public function show(Story $story)
    {
        return view('stories.show', compact('story'));
    }
}
