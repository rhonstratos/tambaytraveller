<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tours extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tours';

    protected $fillable = [
        'travel_id',
        'name',
        'start_date',
        'end_date',
        'price',
    ];

    protected $hidden = [];

    protected $casts = [];

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }
}
