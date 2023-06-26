<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TourRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

/**
 * @group Admin API Endpoints
 *
 * @subGroup Tours
 */
class TourController extends Controller
{
    /**
     * @bodyParam travel_id uuid required The uid of the related travel. Example: 336b2ea1-c979-44ac-9de1-1d36a1ef1005
     * @bodyParam name string required The name of the tour. Example: Tour name 1
     * @bodyParam start_date date required The start date of the tour. Example: 2023-06-26
     * @bodyParam end_date date required The end date of the tour, must be after `start_date`. Example: 2023-06-26
     * @bodyParam price double|float|int required The name of the tour. Example: 123.45
     */
    public function store(Travel $travel, TourRequest $request): TourResource
    {
        $tour = $travel->tours()->create($request->validated());

        return new TourResource($tour);
    }
}
