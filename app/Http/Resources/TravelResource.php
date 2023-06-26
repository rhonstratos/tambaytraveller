<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Travel
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $num_of_days
 * @property int $num_of_nights
 */
class TravelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'num_of_days' => $this->num_of_days,
            'num_of_nights' => $this->num_of_nights,
        ];
    }
}
