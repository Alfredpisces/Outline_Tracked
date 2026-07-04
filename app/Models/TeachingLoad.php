<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingLoad extends Model
{
    protected $fillable = ['teacher_id', 'subject', 'grade_level'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
