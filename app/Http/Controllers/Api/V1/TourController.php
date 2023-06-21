<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Models\Tours;
use App\Models\Travel;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Travel $travel)
    {
        return TourResource::collection(
            $travel
                ->tours()
                ->orderBy('start_date')
                ->paginate()
        );
    }
}
