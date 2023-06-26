<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ToursListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

class TourController extends Controller
{
    public function index(Travel $travel, ToursListRequest $request)
    {
        return TourResource::collection(
            $travel
                ->tours()
                ->when(
                    $request->date_from,
                    fn($q) => $q->where('start_date', '>=', $request->date_from)
                )
                ->when(
                    $request->date_to,
                    fn($q) => $q->where('end_date', '<=', $request->date_to)
                )
                ->when(
                    $request->price_from,
                    fn($q) => $q->where('price', '>=', $request->price_from * 100)
                )
                ->when(
                    $request->price_to,
                    fn($q) => $q->where('price', '<=', $request->price_to * 100)
                )
                ->when(
                    $request->sort_by && $request->sort_order,
                    fn($q) => $q->orderBy($request->sort_by, $request->sort_order)
                )
                ->orderBy('start_date')
                ->paginate()
        );
    }
}
