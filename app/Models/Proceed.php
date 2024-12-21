<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proceed extends Model
{
    use HasFactory;

    protected $table = 'athlete_fee';

    protected $fillable = [
        'deduct_at',
    ];

    protected $casts = [
        'payed_at' => 'datetime',
        'custom_amount' => 'float',
        'deduct_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('payed', function (Builder $builder) {
            $builder->whereNotNull('payed_at');
        });
    }

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function scopeToDeduct(Builder $query): void
    {
        $query->whereNull('deduct_at');
    }

    public function scopeDeducted(Builder $query): void
    {
        $query->whereNotNull('deduct_at');
    }
}
