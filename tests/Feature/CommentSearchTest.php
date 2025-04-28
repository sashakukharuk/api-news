<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\News;
use App\Models\Comment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\DB;

class CommentSearchTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;
    private News $news;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $this->news = News::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    #[Test]
    public function can_filter_comments_by_body_text()
    {
        Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Test comment with specific text'
        ]);

       Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Other comment text'
        ]);

        $response = $this->getJson('/api/comments?body=specific');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'body' => 'Test comment with specific text'
                    ]
                ]
            ]);
    }

    #[Test]
    public function can_filter_comments_by_partial_body_text()
    {
        Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'First comment about Laravel'
        ]);

        Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Second comment about PHP'
        ]);

        Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Third comment about JavaScript'
        ]);

       
        $response = $this->getJson('/api/comments?body=comment');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    ['body' => 'First comment about Laravel'],
                    ['body' => 'Second comment about PHP'],
                    ['body' => 'Third comment about JavaScript']
                ]
            ]);
    }

    #[Test]
    public function returns_empty_result_when_no_matches_found()
    {
        Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'First comment'
        ]);

        Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'Second comment'
        ]);

        $response = $this->getJson('/api/comments?body=nonexistent');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    #[Test]
    public function can_filter_comments_by_case_insensitive_body_text()
    {
        Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'LARAVEL is awesome'
        ]);

        Comment::factory()->create([
            'user_id' => $this->user->id,
            'news_id' => $this->news->id,
            'body' => 'PHP is great'
        ]);

        $response = $this->getJson('/api/comments?body=laravel');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'body' => 'LARAVEL is awesome'
                    ]
                ]
            ]);
    }
}