<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Travel extends Model
{
    use HasFactory, HasUuids, Sluggable;

    protected $table = 'travels';

    protected $fillable = [
        'is_public',
        'slug',
        'name',
        'description',
        'num_of_days',
    ];

    protected $hidden = [];

    protected $casts = [];

    public function tours(): HasMany
    {
        return $this->hasMany(Tours::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function numOfNights(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['num_of_days'] - 1
        );
    }
}
