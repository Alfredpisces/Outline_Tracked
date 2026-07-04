<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LisStaging extends Model
{
    use HasFactory;

    protected $table = 'lis_stagings';

    protected $fillable = [
        'lrn',
        'last_name',
        'first_name',
        'middle_name',
        'extension_name',
        'gender',
        'birthdate',
        'grade_level',
        'section',
        'curriculum',
        'status',
        'school_year',
    ];
}
