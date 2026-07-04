<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotObservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Tell Laravel to automatically handle the JSON scores and Date casting
    protected $casts = [
        'scores' => 'array',
        'observation_date' => 'date',
    ];

    // The teacher being observed
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // The School Head / Master Teacher doing the observing
    public function observer()
    {
        return $this->belongsTo(User::class, 'observer_id');
    }
}
