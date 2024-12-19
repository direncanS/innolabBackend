<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $table = 'libraries';

    protected $fillable = [
        'fid',
        'name',
        'address',
        'latitude',
        'longitude',
        'telephone',
        'email',
        'opening_times_1',
        'opening_times_2',
        'opening_times_3',
        'opening_times_4',
        'opening_times_5',
    ];

    public function getLocationAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "{$this->latitude}, {$this->longitude}";
        }

        return null;
    }
}
