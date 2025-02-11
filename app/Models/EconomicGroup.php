<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EconomicGroup extends Model
{
    /** @use HasFactory<\Database\Factories\EconomicGroupFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
    ];
}
