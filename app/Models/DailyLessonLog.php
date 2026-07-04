<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyLessonLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'reviewer_id',
        'subject',
        'grade_quarter',
        'teaching_dates',

        // NEW: Weekly Date Fields
        'week_start_date',
        'week_end_date',
        'due_at',

        'content_standard',
        'competencies',
        'resources',

        // Standard MATATAG procedures (Kept as fallback/dummy data to prevent SQL errors)
        'procedure_a',
        'procedure_b',
        'procedure_c',
        'procedure_d',
        'procedure_e',

        'passed_count',
        'remedial_count',
        'reflection_notes',
        'principal_feedback',
        'status',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'week_start_date' => 'date',
        'week_end_date' => 'date',
        'due_at' => 'datetime',
        'passed_count' => 'integer',
        'remedial_count' => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // THIS IS THE FIX: Tells the model how to save the M-F daily entries
    public function entries(): HasMany
    {
        return $this->hasMany(DllEntry::class, 'daily_lesson_log_id');
    }
}
