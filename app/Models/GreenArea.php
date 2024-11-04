<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenArea extends Model
{
    use HasFactory;

    protected $table = 'green_areas';

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
    ];
}
