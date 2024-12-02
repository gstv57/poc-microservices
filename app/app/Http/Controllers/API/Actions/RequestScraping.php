<?php

namespace App\Http\Controllers\API\Actions;

use App\Http\Controllers\Controller;
use App\Jobs\RequestScrapingJob;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class RequestScraping extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'query' => 'required|string',
        ]);

        $data = array_merge($data, [
            'user_id' => auth()->id(),
            'hash'    => (string) Str::uuid(),
        ]);

        $order = \App\Models\RequestScraping::create($data);

        dispatch(new RequestScrapingJob($order->only(['hash', 'query'])))->onQueue('scraping');

        return response()->json([
            'status'       => $order->status->name,
            'query_params' => $data['query'],
            'uuid_query'   => $data['hash'],
        ], 201);
    }
}
