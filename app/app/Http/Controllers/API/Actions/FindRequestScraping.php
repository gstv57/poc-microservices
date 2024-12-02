<?php

namespace App\Http\Controllers\API\Actions;

use App\Enum\RequestScrapingStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityResource;

final class FindRequestScraping extends Controller
{
    public function __invoke(\App\Models\RequestScraping $hash)
    {
        $hash->load('opportunities');

        $opportunities = $hash->opportunities()->paginate(20);

        $response = [
            'status' => $hash->status->name,
            'hash'   => $hash->hash,
        ];

        if ($hash->status === RequestScrapingStatus::PENDING) {
            $response['message'] = 'Please wait a few minutes, the scraping is still in progress.';

            return response()->json($response);
        }

        if ($opportunities->isEmpty()) {
            $response['message'] = 'No opportunities found for this request.';

            return response()->json($response);
        }

        $response['data'] = OpportunityResource::collection($opportunities);

        $response['pagination'] = [
            'current_page' => $opportunities->currentPage(),
            'per_page'     => $opportunities->perPage(),
            'total'        => $opportunities->total(),
            'last_page'    => $opportunities->lastPage(),
        ];

        return response()->json($response);
    }
}
