<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payed_at'
    ];

    protected $casts = [
        'payed_at' => 'datetime',
    ];

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }
}
