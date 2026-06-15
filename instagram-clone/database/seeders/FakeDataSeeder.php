<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];

        foreach (range(1, 10) as $i) {
            $users[] = User::create([
                'name' => "FakeUser $i",
                'email' => "fakeuser{$i}@seed.com",
                'password' => Hash::make('password'),
                'username' => "fakeuser_{$i}",
            ]);
        }

        foreach ($users as $user) {
            foreach (range(1, rand(2, 5)) as $i) {
                Post::create([
                    'user_id' => $user->id,
                    'image_path' => 'posts/placeholder.jpg',
                    'caption' => "Post $i by {$user->username}",
                ]);
            }
        }

        foreach ($users as $user) {
            $others = collect($users)->where('id', '!=', $user->id)->random(rand(1, 3));
            foreach ($others as $other) {
                DB::table('follows')->insertOrIgnore([
                    'follower_id' => $user->id,
                    'following_id' => $other->id,
                ]);
            }
        }
    }
}
