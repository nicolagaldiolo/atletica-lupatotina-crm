<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AthleteRace extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'fee_id',
        'subscription_at'
    ];

    protected $casts = [
        'subscription_at' => 'datetime',
    ];

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
}
