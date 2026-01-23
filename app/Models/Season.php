<?php

namespace App\Models;

use App\Traits\Owner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Season extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Owner;

    protected $fillable = [
        'name',
        'start_at',
        'end_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function orderRows(): HasManyThrough
    {
        return $this->hasManyThrough(OrderRow::class, Order::class);
    }
}
