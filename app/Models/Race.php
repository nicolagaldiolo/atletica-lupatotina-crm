<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Race extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'distance',
        'date',
        'is_subscrible',
        'subscrible_expiration'        
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
        'subscrible_expiration' => 'datetime',
        'is_subscrible' => 'boolean'
    ];

    public function athleteFee(): HasManyThrough
    {
        return $this->hasManyThrough(AthleteFee::class, Fee::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }
}
