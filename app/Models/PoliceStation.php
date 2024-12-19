<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoliceStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'fid',
        'name',
        'address',
        'latitude',
        'longitude',
        'se_sdo_rowid',
        'se_anno_cad_data',
    ];

    public function getLocationAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "{$this->latitude}, {$this->longitude}";
        }
        return null;
    }
}
