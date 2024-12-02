<?php

namespace App\Http\Controllers\API;

use App\Enum\OpportunityStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

final class OpportunityController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return OpportunityResource::collection(Opportunity::where('status', OpportunityStatus::ACCEPTED)->paginate(20));
    }
    public function show(Opportunity $opportunity): OpportunityResource
    {
        return OpportunityResource::make($opportunity);
    }

    public function store(): OpportunityResource
    {
        $data = request()->validate([
            'title'    => 'required',
            'url'      => 'required|unique:opportunities,url',
            'details'  => 'required',
            'business' => 'required',
        ]);

        $data['user_id'] = auth()->id();
        $data['status']  = OpportunityStatus::PENDING;
        $opportunity     = Opportunity::create($data);

        return OpportunityResource::make($opportunity);
    }

    public function update(Opportunity $opportunity): OpportunityResource
    {
        Gate::authorize('update', $opportunity);

        $data = request()->validate([
            'title'    => 'required',
            'url'      => 'required|unique:opportunities,url,' . $opportunity->id,
            'details'  => 'required',
            'business' => 'required',
        ]);

        $opportunity->update($data);

        return OpportunityResource::make($opportunity);
    }
    public function destroy(Opportunity $opportunity)
    {
        Gate::authorize('delete', $opportunity);

        $opportunity->delete();

        return response()->noContent();
    }
}
