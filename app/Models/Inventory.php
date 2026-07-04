<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_number', 'item_name', 'category', 'lifespan_type',
        'condition', 'quantity', 'unit', 'reorder_level',
        'assignment_type', 'location', 'assigned_to',
        'funding_source', 'date_acquired', 'expiry_date', 'photo_path'
    ];

    protected $casts = [
        'date_acquired' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * The Teacher or Coordinator assigned to this asset.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * The complete history of this asset (borrows, consumption, repairs).
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(AssetTransaction::class)->latest();
    }
}
