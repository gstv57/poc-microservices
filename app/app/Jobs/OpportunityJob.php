<?php

namespace App\Jobs;

use App\Models\{Opportunity, RequestScraping};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class OpportunityJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $data)
    {
    }

    public function handle(): void
    {
        $opportunity = Opportunity::updateOrCreate(
            ['url' => $this->data['url']],
            [
                'title'    => $this->data['title'],
                'details'  => $this->data['details'],
                'business' => $this->data['business'],
            ]
        );

        if (!empty($this->data['hash'])) {
            $this->associateRequestScraping($opportunity);
        }
    }

    private function associateRequestScraping(Opportunity $opportunity): void
    {
        $requestScraping = RequestScraping::where('hash', $this->data['hash'])->first();

        if ($requestScraping) {
            $opportunity->user_id = $requestScraping->user_id;
            $opportunity->save();

            $requestScraping->opportunities()->syncWithoutDetaching([$opportunity->id]);
        } else {
            Log::warning("RequestScraping with hash {$this->data['hash']} not found.");
        }
    }
}
