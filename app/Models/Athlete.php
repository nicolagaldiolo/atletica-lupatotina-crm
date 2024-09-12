<?php

namespace App\Models;

use App\Traits\ModelStorage;
use Illuminate\Support\Str;
use Database\Factories\AthleteFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Athlete extends Model
{
    use HasFactory,
        ModelStorage,
        SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'gender',
        'phone',
        'email',
        'address',
        'zip',
        'city',
        'birth_place',
        'birth_date',
        'registration_number',
        'size',
        '10k',
        'half_marathon',
        'marathon'
    ];

    protected $casts = [
        'birth_date' => 'datetime'
    ];

    protected $appends = [
        'fullname'
    ];

    protected static function newFactory(): Factory
    {
        return AthleteFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('surname', 'asc')->orderBy('name', 'asc');
        });
    }

    /*public function races(): BelongsToMany
    {
        return $this->belongsToMany(Race::class)
            ->withTimestamps()
            ->using(AthleteRace::class)
            ->as('athleterace')
            ->withPivot('id', 'subscription_at', 'fee_id');
    }
    */

    public function getFullnameAttribute()
    {
        return implode(' ', [$this->surname, $this->name]);
    }

    public function feesToPay(): BelongsToMany
    {
        return $this->belongsToMany(Fee::class)
            ->withTimestamps()
            ->using(AthleteFee::class)
            ->as('athletefee')
            ->withPivot(['payed_at', 'custom_amount'])->wherePivot('payed_at', null);
    }

    public function fees(): BelongsToMany
    {
        return $this->belongsToMany(Fee::class)
            ->withTimestamps()
            ->using(AthleteFee::class)
            ->as('athletefee')
            ->withPivot(['payed_at', 'custom_amount']);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class)->current();
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    public function validVouchers(): HasMany
    {
        return $this->hasMany(Voucher::class)->whereDoesntHave('athleteFee');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
