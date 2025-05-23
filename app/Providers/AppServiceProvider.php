<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Http\Middleware\MultiAuthMiddleware;
use App\Http\Middleware\CacheResponseMiddleware;
use App\Http\Middleware\RateLimitMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('multi.auth', function ($app) {
            return new MultiAuthMiddleware();
        });

        $this->app->bind('cache.response', function ($app) {
            return new CacheResponseMiddleware();
        });

        $this->app->bind('rate.limit', function ($app) {
            return new RateLimitMiddleware();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Comment::class, CommentPolicy::class);

        DB::listen(function ($query) {
            Log::info('SQL Query Executed', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time_ms' => $query->time,
            ]);
        });
    }
}
