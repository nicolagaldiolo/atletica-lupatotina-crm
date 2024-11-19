<?php

namespace App\Models;

use Database\Factories\AthleteFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Traits\HasAvatar;
use App\Traits\ModelStorage;

class Athlete extends Model
{
    use HasFactory,
        SoftDeletes,
        EagerLoadPivotTrait,
        ModelStorage,
        HasAvatar;

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
        'marathon',
        'invited_at'
    ];

    protected $casts = [
        'birth_date' => 'datetime',
        'invited_at' => 'datetime'
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

    public function getFullnameAttribute()
    {
        return implode(' ', [$this->surname, $this->name]);
    }

    public function getBirthDateFormattedAttribute()
    {
        return $this->birth_date ? $this->birth_date->format('Y-m-d') : null;
    }
    
    public function feesToPay(): BelongsToMany
    {
        return $this->belongsToMany(Fee::class)
            ->withTimestamps()
            ->using(AthleteFee::class)
            ->as('athletefee')
            ->withPivot(['payed_at', 'custom_amount', 'voucher_id'])->wherePivot('payed_at', null);
    }

    public function fees(): BelongsToMany
    {
        return $this->belongsToMany(Fee::class)
            ->withTimestamps()
            ->using(AthleteFee::class)
            ->as('athletefee')
            ->withPivot(['payed_at', 'custom_amount', 'voucher_id']);
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
