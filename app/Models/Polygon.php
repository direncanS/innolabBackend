<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polygon extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_code',
        'name',
        'shape',
    ];

    protected $casts = [
        'shape' => 'array', // If you want to store JSON data for the shape
    ];
}
