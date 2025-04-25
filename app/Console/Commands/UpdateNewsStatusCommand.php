<?php

namespace App\Console\Commands;

use App\Jobs\UpdateNewsStatusJob;
use Illuminate\Console\Command;

class UpdateNewsStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update news status based on creation date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching UpdateNewsStatusJob...');
        UpdateNewsStatusJob::dispatch();
        $this->info('Job dispatched successfully!');
    }
} 