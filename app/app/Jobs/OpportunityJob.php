<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class OpportunityJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
        //
    }
    
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        echo json_encode($this->data) . PHP_EOL;
    }
}
