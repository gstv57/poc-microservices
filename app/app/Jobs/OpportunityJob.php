<?php

namespace App\Jobs;

use App\Models\Opportunity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class OpportunityJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $data)
    {
        //
    }
    public function handle(): void
    {
        Log::info(print_r($this->data, true));
        try {
            Opportunity::create($this->data);
            echo json_encode($this->data['title'] . 'inserted with successfully') . PHP_EOL;
        } catch (UniqueConstraintViolationException $e) {
            echo json_encode($this->data['data'] . 'exists in database' . PHP_EOL);
        }
    }
}
