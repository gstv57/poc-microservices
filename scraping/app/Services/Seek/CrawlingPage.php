<?php

namespace App\Services\Seek;

use App\Jobs\OpportunityJob;
use DOMDocument;
use DOMXPath;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class CrawlingPage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public string $url = 'https://www.seek.com.au';
    public function __construct(public array $data)
    {
        $this->url = $this->url . $this->data['url'];
    }
    public function handle(): void
    {
        $html = file_get_contents($this->url);

        $dom = new DOMDocument();

        @$dom->loadHTML($html);

        $xpath      = new DOMXPath($dom);
        $advertiser = $xpath->query('//span[@data-automation="advertiser-name"]')->item(0)->nodeValue ?? null;
        $detail     = $xpath->query('//span[@data-automation="job-detail-classifications"]')->item(0)->nodeValue ?? null;

        $new_jobs = [
            'title'    => $this->data['title'],
            'url'      => $this->url,
            'details'  => $detail ?? 'N/A',
            'business' => $advertiser ?? 'N/A',
            'hash'     => $this->data['hash'],
        ];

        dispatch(new OpportunityJob($new_jobs))->onQueue('opportunities');
    }
}
