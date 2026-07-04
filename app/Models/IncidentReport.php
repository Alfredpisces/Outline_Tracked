<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncidentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'student_id',
        'incident_date',
        'severity',
        'offense_type',
        'narrative',
        'status',
        'counselor_id',
        'counselor_notes',
        'resolved_at',
        'principal_action',
        'evidence_path', // Added this to allow the file path to be saved
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'resolved_at'   => 'datetime',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(CounselingIntervention::class);
    }
}
