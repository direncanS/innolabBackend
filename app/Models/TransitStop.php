<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransitStop extends Model
{
    use HasFactory;

    protected $table = 'transit_stops';

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
    ];
}
