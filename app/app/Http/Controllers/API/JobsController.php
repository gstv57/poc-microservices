<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return OpportunityResource::collection(Opportunity::paginate(20));
    }

    public function show(Opportunity $opportunity): OpportunityResource
    {
        return OpportunityResource::make($opportunity);
    }
}
