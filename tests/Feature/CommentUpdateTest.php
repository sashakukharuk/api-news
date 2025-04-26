<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\News;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CommentUpdateTest extends TestCase
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
    public function user_can_update_own_comment()
    {
        // Create a comment
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Original comment'
        ]);

        $updateData = [
            'body' => 'Updated comment',
            'news_id' => $this->news->id
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $comment->id,
                    'body' => 'Updated comment',
                    'news_id' => $this->news->id,
                    'user_id' => $this->user->id
                ]
            ]);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Updated comment'
        ]);
    }

    #[Test]
    public function cannot_update_comment_when_rate_limited()
    {
        // Create a comment
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Original comment'
        ]);

        $updateData = [
            'body' => 'Updated comment',
            'news_id' => $this->news->id
        ];

        // Simulate rate limiting by making multiple update requests
        for ($i = 0; $i < 60; $i++) {
            $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->putJson("/api/comments/{$comment->id}", $updateData);
        }

        // Try to update one more time
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(429)
            ->assertJson([
                'message' => 'Too many requests. Please try again later.'
            ]);
    }

    #[Test]
    public function cannot_update_nonexistent_comment()
    {
        $updateData = [
            'body' => 'Updated comment',
            'news_id' => $this->news->id
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/comments/999', $updateData);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Resource not found.'
            ]);
    }

    #[Test]
    public function cannot_update_comment_without_authentication()
    {
        // Create a comment
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Original comment'
        ]);

        $updateData = [
            'body' => 'Updated comment',
            'news_id' => $this->news->id
        ];

        $response = $this->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
                'error' => 'Authentication required'
            ]);
    }

    #[Test]
    public function cannot_update_other_users_comment()
    {
        // Create another user
        $otherUser = User::factory()->create();

        // Create a comment by another user
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'news_id' => $this->news->id,
            'body' => 'Original comment'
        ]);

        $updateData = [
            'body' => 'Updated comment',
            'news_id' => $this->news->id
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Access denied.'
            ]);
    }

    #[Test]
    public function cannot_update_comment_with_empty_body()
    {
        // Create a comment
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Original comment'
        ]);

        $updateData = [
            'body' => '',
            'news_id' => $this->news->id
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'body'
                ]
            ]);
    }

    #[Test]
    public function cannot_update_comment_with_too_long_body()
    {
        // Create a comment
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Original comment'
        ]);

        $updateData = [
            'body' => str_repeat('a', 1001), // Assuming max length is 1000
            'news_id' => $this->news->id
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'body'
                ]
            ]);
    }
} 