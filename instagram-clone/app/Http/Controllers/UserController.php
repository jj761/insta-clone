<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts;

        return view('users.show', compact('user', 'posts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->where('id', '!=', auth()->id())
            ->limit(20)
            ->get();

        return view('users.search', compact('users', 'query'));
    }
}
