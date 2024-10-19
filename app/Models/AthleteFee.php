<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AthleteFee extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'payed_at',
        'custom_amount',
        'voucher_id'
    ];

    protected $casts = [
        'payed_at' => 'datetime',
        'custom_amount' => 'float'
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if($model->voucher_id){
                $voucher = Voucher::findOrFail($model->voucher_id);
                $model->custom_amount = ($model->custom_amount - $voucher->amount_calculated);
            }
        });
    }

    public function Fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function Athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function Voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
