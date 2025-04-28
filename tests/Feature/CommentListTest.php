<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\News;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CommentListTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private News $news;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        // Create test news
        $this->news = News::factory()->create([
            'user_id' => $this->user->id
        ]);

        // Get authentication token
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device_name' => 'test-device'
        ]);

        $this->token = $response->json('token');
    }

    #[Test]
    public function can_get_list_of_comments()
    {
        // Create multiple comments
        $comments = Comment::factory(5)->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id
        ]);

        $response = $this->getJson('/api/comments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'news_id',
                        'body',
                        'created_at',
                        'updated_at',
                        'user' => [
                            'id',
                            'name',
                            'email',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);
    }

    #[Test]
    public function can_filter_comments_by_user_id()
    {
        // Create another user
        $otherUser = User::factory()->create();

        // Create comments for both users
        Comment::factory(3)->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id
        ]);

        Comment::factory(2)->create([
            'user_id' => $otherUser->id,
            'news_id' => $this->news->id
        ]);

        $response = $this->getJson("/api/comments?user_id={$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function can_filter_comments_by_news_id()
    {
        // Create another news
        $otherNews = News::factory()->create([
            'user_id' => $this->user->id
        ]);

        // Create comments for both news
        Comment::factory(3)->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id
        ]);

        Comment::factory(2)->create([
            'user_id' => $this->user->id,
            'news_id' => $otherNews->id
        ]);

        $response = $this->getJson("/api/comments?news_id={$this->news->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function can_filter_comments_by_user_email()
    {
        // Create another user with different email
        $otherUser = User::factory()->create([
            'email' => 'other@example.com'
        ]);

        // Create comments for both users
        Comment::factory(3)->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id
        ]);

        Comment::factory(2)->create([
            'user_id' => $otherUser->id,
            'news_id' => $this->news->id
        ]);

        $response = $this->getJson('/api/comments?user_email=test@example.com');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function can_filter_comments_by_date_range()
    {
        // Create comments with different dates
        $oldComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'created_at' => now()->subDays(5)
        ]);

        $newComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'created_at' => now()
        ]);

        $response = $this->getJson('/api/comments?from_date=' . now()->subDays(3)->toDateTimeString());

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $newComment->id
                    ]
                ]
            ]);
    }

    #[Test]
    public function can_paginate_comments()
    {
        // Create more comments than default per_page
        Comment::factory(15)->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id
        ]);

        $response = $this->getJson('/api/comments?page=2&per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJson([
                'meta' => [
                    'current_page' => 2,
                    'per_page' => 10,
                    'total' => 15
                ]
            ]);
    }
} 