<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\News;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CommentTest extends TestCase
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
    public function user_can_create_comment()
    {
        $commentData = [
            'body' => 'This is a test comment',
            'news_id' => $this->news->id
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/comments', $commentData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
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
                    ],
                    'news' => [
                        'id',
                        'title',
                        'description',
                        'is_new',
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
                ]
            ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'This is a test comment',
            'news_id' => $this->news->id,
            'user_id' => $this->user->id
        ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_create_comment()
    {
        $commentData = [
            'body' => 'This is a test comment',
            'news_id' => $this->news->id
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
                'error' => 'Authentication required'
            ]);
    }

    #[Test]
    public function cannot_create_comment_without_required_fields()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/comments', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'body',
                    'news_id'
                ]
            ]);
    }

    #[Test]
    public function cannot_create_comment_for_nonexistent_news()
    {
        $commentData = [
            'body' => 'This is a test comment',
            'news_id' => 999
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/comments', $commentData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The selected news id is invalid.',
                'errors' => [
                    'news_id' => [
                        'The selected news id is invalid.'
                    ]
                ]
            ]);
    }

    #[Test]
    public function cannot_create_comment_with_empty_body()
    {
        $commentData = [
            'body' => '',
            'news_id' => $this->news->id
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/comments', $commentData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'body'
                ]
            ]);
    }

    #[Test]
    public function cannot_create_comment_with_too_long_body()
    {
        $commentData = [
            'body' => str_repeat('a', 1001), // Assuming max length is 1000
            'news_id' => $this->news->id
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/comments', $commentData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'body'
                ]
            ]);
    }

    #[Test]
    public function cannot_create_comment_when_rate_limited()
    {
        // Simulate rate limiting by making multiple requests
        for ($i = 0; $i < 60; $i++) {
            $commentData = [
                'body' => "Test comment $i",
                'news_id' => $this->news->id
            ];

            $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->postJson('/api/comments', $commentData);
        }

        // Try to create one more comment
        $commentData = [
            'body' => 'This should be rate limited',
            'news_id' => $this->news->id
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/comments', $commentData);

        $response->assertStatus(429)
            ->assertJson([
                'message' => 'Too many requests. Please try again later.'
            ]);
    }
} 