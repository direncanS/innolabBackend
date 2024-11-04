<?php

namespace App\Models\AirQuality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = ['station_id', 'component_id', 'measurement_time', 'value', 'unit'];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
