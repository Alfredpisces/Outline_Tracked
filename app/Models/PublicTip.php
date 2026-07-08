<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTip extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_name',
        'reporter_contact',
        'incident_location',
        'narrative',
        'evidence_path',
        'video_link',
        'status',
    ];

    public function isAnonymous(): bool
    {
        return empty($this->reporter_name);
    }
}
