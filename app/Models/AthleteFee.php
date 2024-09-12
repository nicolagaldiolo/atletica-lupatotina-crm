<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

    public function Fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function Athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }
}
