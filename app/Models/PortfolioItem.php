<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'level',
        'date_earned',
        'description',
        'file_path',
    ];

    protected $casts = [
        'date_earned' => 'date',
    ];

    /**
     * Get the user (teacher) that owns this portfolio item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
