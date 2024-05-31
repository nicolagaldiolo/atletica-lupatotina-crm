<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Athlete extends Model
{
    use HasFactory;
    use SoftDeletes;

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
    ];

    protected $casts = [
        'birth_date' => 'datetime'
    ];

    protected $appends = [
        'fullname',
        'certificate'
    ];

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


    public function feesToPay(): BelongsToMany
    {
        return $this->belongsToMany(Fee::class)
            ->withTimestamps()
            ->using(AthleteFee::class)
            ->as('athletefee')
            ->withPivot('payed_at')->wherePivot('payed_at', null);
    }


    public function fees(): BelongsToMany
    {
        return $this->belongsToMany(Fee::class)
            ->withTimestamps()
            ->using(AthleteFee::class)
            ->as('athletefee')
            ->withPivot('payed_at');
    }


    public function getFullnameAttribute()
    {
        return implode(' ', [$this->surname, $this->name]);
    }

    public function getCertificateAttribute()
    {
        return Carbon::now();
    }
}
