<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Voucher extends Model
{
    use HasFactory,
    SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'amount'
    ];

    protected $appends = [
        'used_at'
    ];

    protected $casts = [
        'amount' => 'float',
        'used_at' => 'datetime'
    ];

    public function Athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function AthleteFee(): HasOne
    {
        return $this->hasOne(AthleteFee::class);
    }

    public function getUsedAtAttribute()
    {
        if($this->athleteFee){
            return $this->athleteFee->created_at->toIsoString();
        }
        return null;
    }
}
