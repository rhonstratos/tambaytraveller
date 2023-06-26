<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

/**
 * @group Admin API Endpoints
 *
 * @subGroup Travel
 */
class TravelController extends Controller
{
    /**
     * @bodyParam name string required The name of the travel. Example: Travel name 1
     * @bodyParam description string required The description of the travel. Example: lorem ipsum
     * @bodyParam is_public boolean The public status of the travel. Example: 0|1
     * @bodyParam num_of_days int required The name of the travel. Example: 5
     */
    public function store(TravelRequest $request): TravelResource
    {
        $travel = Travel::create($request->validated());

        return new TravelResource($travel);
    }

    /**
     * @urlParam travel_id uuid The uuid of the travel. Example: 336b2ea1-c979-44ac-9de1-1d36a1ef1005
     *
     * @bodyParam name string The name of the travel. Example: New travel name 1
     * @bodyParam description string The description of the travel. Example: lorem ipsum 2
     * @bodyParam is_public boolean The public status of the travel. Example: 0|1
     * @bodyParam num_of_days int The name of the travel. Example: 10
     *
     * @response TravelResource
     */
    public function update(Travel $travel, TravelRequest $request): TravelResource
    {
        $travel->update($request->validated());

        return new TravelResource($travel);
    }
}
