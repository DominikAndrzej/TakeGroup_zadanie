<?php

namespace App\Console\Commands;

use App\Jobs\ImportTmdbDataJob;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportTmdbData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmdb:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from TMDB API and put them in DB';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            ImportTmdbDataJob::dispatch();
            $this->info('tmdb import has been added to queue');
        } catch (Exception $e) {
            Log::error('Command tmdb:import failed: ' . $e->getMessage());
            $this->error('Failed to dispatch the job: ' . $e->getMessage());
        }
    }
}
