<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Vyache',
            'email' => 'vyache@mail.ru',
            'password' => bcrypt('password'),
        ]);

        $users = User::factory()->count(4)->create();

        $posts = Post::factory()->count(10)->create([
            'user_id' => fn() => User::all()->random()->id,
        ]);

        Comment::factory()->count(25)->create([
            'post_id' => fn() => Post::all()->random()->id,
            'user_id' => fn() => User::all()->random()->id,
        ]);
    }
}
