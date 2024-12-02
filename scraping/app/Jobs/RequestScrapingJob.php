<?php

namespace App\Jobs;

use App\Services\Seek\GetJobsOnPage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RequestScrapingJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        dispatch(new GetJobsOnPage($this->data['query'] . '-jobs', $this->data['hash']));
    }
}
