<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\News;
use App\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $news = News::factory(5)->create();

        $users = User::factory(5)->create();

        foreach ($news as $newsItem) {
            $commentCount = rand(2, 3);
            
            for ($i = 0; $i < $commentCount; $i++) {
                Comment::factory()->create([
                    'news_id' => $newsItem->id,
                    'user_id' => $users->random()->id,
                ]);
            }
        }

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
