<?php

namespace App\Http\Controllers;

class FollowController extends Controller
{
    public function store($userId)
    {
        auth()->user()->following()->attach($userId);

        return back();
    }

    public function destroy($userId)
    {
        auth()->user()->following()->detach($userId);

        return back();
    }
}
