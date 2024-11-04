<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class);
    }

    public function polygons()
    {
        return $this->hasMany(Polygon::class, 'district_code', 'district_code');
    }
}
