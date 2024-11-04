<?php

namespace App\Models\AirQuality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }
}
