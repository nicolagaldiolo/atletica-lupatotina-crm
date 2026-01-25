<?php

namespace App\Models;

use App\Traits\Owner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Owner;

    protected $fillable = [
        'season_id',
        'athlete_id',
        'quantity',
        'total_amount'
    ];

    protected $appends = [
        'status'
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

    public function getStatusAttribute()
    {


        $orderStatus = DB::table('order_rows')
        ->where('order_id', $this->order_id)
        ->selectRaw("
            CASE
                WHEN COUNT(*) = SUM(status = 'cancelled') THEN 'cancelled'
                WHEN COUNT(*) = SUM(status = 'delivered') THEN 'delivered'
                WHEN SUM(status = 'delivered') > 0 THEN 'partially_delivered'
                WHEN SUM(status = 'processing') > 0 THEN 'processing'
                ELSE 'pending'
            END AS order_status
        ")->value('order_status');
        
        return $orderStatus;
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function rows(): HasMany
    {
        return $this->hasMany(OrderRow::class);
    }
}
