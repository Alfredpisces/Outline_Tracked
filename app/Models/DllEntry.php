<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DllEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_lesson_log_id',
        'entry_date',
        'procedure_a',
        'procedure_b',
        'procedure_c',
        'procedure_d',
        'procedure_e',
        'resources',
        'passed_count',
        'remedial_count',
        'reflection_notes'
    ];

    // ADD THIS BLOCK: It tells Laravel to treat entry_date as a Carbon Date object
    protected $casts = [
        'entry_date' => 'date',
    ];

    public function dailyLessonLog()
    {
        return $this->belongsTo(DailyLessonLog::class);
    }
}
