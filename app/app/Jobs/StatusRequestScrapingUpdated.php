<?php

namespace App\Jobs;

use App\Models\RequestScraping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StatusRequestScrapingUpdated implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
    }
    public function handle(): void
    {
        RequestScraping::where('hash', $this->data['hash'])
            ->update(['status' => $this->data['status']]);
    }
}
