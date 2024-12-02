<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @uses \App\Models\Opportunity
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $details
 * @property string $business
 */
class OpportunityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'url'        => $this->url,
            'details'    => $this->details,
            'business'   => $this->business,
            'status'     => strtoupper($this->status->name),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
