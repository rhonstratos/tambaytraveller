<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Roles extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'roles';
    protected $fillable = ['name'];
    protected $hidden = [];
    protected $casts = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}