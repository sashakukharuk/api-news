<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\News;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CommentDeleteTest extends TestCase
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
    public function user_can_delete_own_comment()
    {
        // Create a comment
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Test comment for deletion'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Comment deleted successfully'
            ]);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }

    #[Test]
    public function cannot_delete_comment_when_rate_limited()
    {
        // Create a comment first
        $comments = Comment::factory(7)->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Test comment for deletion'
        ]);

        // Simulate rate limiting by making multiple delete requests
        for ($i = 0; $i < 5; $i++) {
            $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->deleteJson("/api/comments/{$comments[$i]->id}");
        }

        // Try to delete one more time
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/comments/{$comments[6]->id}");

        $response->assertStatus(429)
            ->assertJson([
                'message' => 'Too many requests. Please try again later.'
            ]);
    }

    #[Test]
    public function cannot_delete_nonexistent_comment()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/comments/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Resource not found.'
            ]);
    }

    #[Test]
    public function cannot_delete_comment_without_authentication()
    {
        // Create a comment
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Test comment for deletion'
        ]);

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
                'error' => 'Authentication required'
            ]);
    }

    #[Test]
    public function cannot_delete_other_users_comment()
    {
        // Create another user
        $otherUser = User::factory()->create();

        // Create a comment by another user
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'news_id' => $this->news->id,
            'body' => 'Test comment for deletion'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Access denied.'
            ]);
    }
} 