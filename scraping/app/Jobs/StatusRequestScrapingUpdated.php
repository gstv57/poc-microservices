<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StatusRequestScrapingUpdated implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $data) {}

    public function handle(): void {}
}
