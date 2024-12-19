<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMarket extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'name',
        'address',
        'district_code',
        'langtext',
        'web_link',
    ];
}
