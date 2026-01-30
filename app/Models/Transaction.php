<?php

namespace App\Models;

use App\Traits\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    use Owner;

    protected $fillable = [
        'payed_at',
        'amount',
        'bank_transfer',
        'cashed_by'
    ];

    protected $casts = [
        'payed_at' => 'datetime',
        'amount' => 'float'
    ];
    
    /**
     * Get the parent commentable model (post or video).
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}
