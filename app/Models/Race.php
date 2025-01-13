<?php

namespace App\Models;

use App\Traits\Owner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Race extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Owner;

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

    public function scopeType($query, $type): void
    {
        $query->where('type', $type);
    }

    public function scopeSubscribeable($query): void
    {
        $query->where('date', '>=', Carbon::now()->startOfDay());
    }
}
