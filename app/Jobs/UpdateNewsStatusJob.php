<?php

namespace App\Jobs;

use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class UpdateNewsStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Start Job:UpdateNewsStatusJob');

        $twoDaysAgo = Carbon::now()->subDays(2);

        News::where('is_new', true)
            ->where('created_at', '<', $twoDaysAgo)
            ->update(['is_new' => false]);

        Log::info('End Job:UpdateNewsStatusJob');
    }
} 