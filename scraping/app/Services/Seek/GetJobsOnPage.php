<?php

namespace App\Services\Seek;

use App\Jobs\StatusRequestScrapingUpdated;
use DOMDocument;
use DOMXPath;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\{Bus};

class GetJobsOnPage implements ShouldQueue
{
    use Queueable;

    protected array $queueList;

    public function __construct(
        public string $keyword,
        public string $hash,
    ) {
        $this->queueList = [];
    }
    public function handle(): void
    {
        $this->scrapeJobPages();
    }

    protected function scrapeJobPages(int $page = 1): void
    {
        $url = "https://www.seek.com.au/$this->keyword?page=$page";

        $html = file_get_contents($url);

        $dom = new DOMDocument();

        @$dom->loadHTML($html);

        $xpath = new DOMXPath($dom);

        $elements = $xpath->query('//a[@data-automation="jobTitle"]');

        if ($elements !== false && $elements->length > 0) {
            $currentPageJobs = $this->extractJobsFromElements($elements);
            $payload         = $this->processJobs($currentPageJobs);

            $this->makeQueue($payload);

            if ($this->hasNextPage($html)) {
                $this->scrapeJobPages($page + 1);
            }
        } else {
            $this->dispatchJobs($this->queueList);
        }
    }

    protected function makeQueue($payload): void
    {
        foreach ($payload as $job) {
            $this->queueList[] = $job;
        }
    }

    protected function extractJobsFromElements($elements): array
    {
        $currentPageJobs = [];

        foreach ($elements as $element) {
            $currentPageJobs[] = [
                'title' => $element->nodeValue,
                'url'   => $element->getAttribute('href'),
            ];
        }

        return $currentPageJobs;
    }

    protected function processJobs(array $currentPageJobs): array
    {
        $payload = [];

        foreach ($currentPageJobs as $value) {

            $payload[] = [
                'job_id' => $this->getJobId($value),
                'title'  => $value['title'],
                'url'    => $value['url'],
                'hash'   => $this->hash ?? null,
            ];
        }

        return array_values(array_intersect_key(
            $payload,
            array_unique(array_column($payload, 'job_id'))
        ));
    }

    protected function dispatchJobs(array $payload): void
    {
        $jobs = [];

        foreach ($payload as $job) {
            $jobs[] = new CrawlingPage($job);
        }

        $hash = $this->hash;

        Bus::batch($jobs)
            ->onQueue('scraping')
            ->then(function () use ($hash) {
                dispatch(new StatusRequestScrapingUpdated([
                    'hash' => $hash,
                    'status' => 'done',
                ]))->onQueue('opportunities');
            })
            ->dispatch();
    }

    protected function getJobId($job): int
    {
        $url   = $job['url'];
        $path  = parse_url($url, PHP_URL_PATH);
        $jobId = basename($path);

        return (int) $jobId;
    }

    protected function hasNextPage($html): bool
    {
        $dom = new DOMDocument();

        @$dom->loadHTML($html);

        $xpath    = new DOMXPath($dom);
        $elements = $xpath->query('//*[@id="app"]/div/div[3]/div/section/div[2]/div/div/div/section/div/h3/text()');

        if ($elements->length > 0) {
            $text = $elements->item(0)->nodeValue;

            return $text !== 'No matching search results';
        }

        return true;
    }
}
