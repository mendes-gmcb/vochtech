<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    /** @use HasFactory<\Database\Factories\BrandFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'economic_group_id',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(EconomicGroup::class, 'economic_group_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
