<?php

namespace App\Models\AirQuality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'short_name', 'long_name', 'latitude', 'longitude', 'component_codes'];

    protected $casts = [
        'component_codes' => 'array',
    ];

    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }
}
