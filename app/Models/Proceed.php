<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proceed extends Model
{
    use HasFactory;

    protected $table = 'athlete_fee';

    protected static function booted(): void
    {
        static::addGlobalScope('payed', function (Builder $builder) {
            $builder->whereNotNull('payed_at');
        });
    }
}
