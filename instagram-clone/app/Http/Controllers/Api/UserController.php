<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        return response()->json([
            'user' => $user,
            'posts_count' => $user->posts()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'posts' => $user->posts()->latest()->get(),
            'followers' => $user->followers()->get(['users.id']),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $users = User::where('username', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->get();

        return response()->json($users);
    }
}
