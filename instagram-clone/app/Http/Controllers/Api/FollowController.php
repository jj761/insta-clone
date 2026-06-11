<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class FollowController extends Controller
{
    public function store($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot follow yourself'], 422);
        }

        auth()->user()->following()->attach($userId);

        return response()->json(['message' => 'Followed successfully']);
    }

    public function destroy($userId)
    {
        auth()->user()->following()->detach($userId);

        return response()->json(['message' => 'Unfollowed successfully']);
    }
}
