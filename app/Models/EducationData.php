<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationData extends Model
{
    use HasFactory;

    protected $table = 'education_data';

    protected $fillable = [
        'nuts',
        'district_code',
        'sub_district_code',
        'ref_year',
        'ref_date',
        'edu_all',
        'edu_leh',
        'edu_bms',
        'edu_ahs',
        'edu_bhs',
        'edu_kol',
        'edu_aca',
        'edu_uni',
        'edu_aka',
    ];
}
