<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AdminDashboard extends Component
{
    public int $totalUsers;

    public int $totalPosts;

    public int $totalFollows;

    public int $totalStories;

    public function mount(): void
    {
        $this->totalUsers = User::where('is_admin', false)->count();
        $this->totalPosts = Post::count();
        $this->totalFollows = DB::table('follows')->count();
        $this->totalStories = Story::count();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
