<?php

namespace App\Console\Commands;

use App\Services\Seek\GetJobsOnPage;
use Illuminate\Console\Command;

class ScrapingTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        dispatch(new GetJobsOnPage('software-enginner-jobs'))->onQueue('scraping');
    }
}
