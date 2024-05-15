<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Race extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'distance',
        'date',
        'is_subscrible',
        'subscrible_expiration',
        'is_visible_on_site',
        'amount'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
        'subscrible_expiration' => 'datetime',
        'is_subscrible' => 'boolean',
        'is_visible_on_site' => 'boolean'
    ];

    public function athletes(): BelongsToMany
    {
        return $this->belongsToMany(Athlete::class)
            ->withTimestamps()
            ->using(AthleteRace::class)
            ->as('athleterace')
            ->withPivot('id', 'subscription_at', 'fee_id');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }
}
