<?php

namespace App\Models;

use App\Traits\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    use Owner;

    protected $fillable = [
        'payed_at',
        'amount',
        'bank_transfer',
        'cashed_by',
        'deduct_at'
    ];

    protected $casts = [
        'payed_at' => 'datetime',
        'amount' => 'float',
        'deduct_at' => 'datetime',
    ];
    
    /**
     * Get the parent commentable model (post or video).
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function cashed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashed_by', 'id');
    }

    public function scopeDeducible(Builder $query): void
    {
        $query->where(function($query){
            $query->byUser();
        })->orWhere(function($query){
            $query->ByBankTransfer();
        });
    }

    public function scopeByUser(Builder $query): void
    {
        $query->where('bank_transfer', 0)->whereNotNull('cashed_by');
    }
    
    public function scopeByBankTransfer(Builder $query): void
    {
        $query->where('bank_transfer', 1)->whereNull('cashed_by');
    }

    public function scopeToDeduct(Builder $query): void
    {
        $query->deducible()->whereNull('deduct_at');
    }

    public function scopeDeducted(Builder $query): void
    {
        $query->deducible()->whereNotNull('deduct_at');
    }
}
