<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetTransaction extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'inventory_id', 'user_id', 'action_type', 'quantity',
        'borrower_name', 'borrower_type', 'borrowed_at',
        'expected_return_date', 'returned_at', 'status',
        'notes', 'photo_path'
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
        'expected_return_date' => 'date',
    ];

    /**
     * The inventory item involved in this transaction.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * The Teacher/User who logged this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
