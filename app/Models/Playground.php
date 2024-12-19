<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playground extends Model
{
    use HasFactory;

    protected $fillable = [
        'fid',
        'objectid',
        'latitude',
        'longitude',
        'name',
        'district_code',
        'playground_detail',
        'type_detail',
    ];
}
