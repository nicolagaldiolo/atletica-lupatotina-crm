<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\Owner;
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
        'status',
        'is_payed'
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
        $order_canceled = OrderStatus::Canceled;
        $order_delivered = OrderStatus::Delivered;
        $order_partially_delivered = OrderStatus::Partially_delivered;
        $order_processing = OrderStatus::Processing;
        $order_pending = OrderStatus::Pending;

        $orderStatus = DB::table('order_rows')->where('order_id', $this->order_id)->selectRaw("
            CASE
                WHEN COUNT(*) = SUM(status = '$order_canceled') THEN '$order_canceled'
                WHEN COUNT(*) = SUM(status = '$order_delivered') THEN '$order_delivered'
                WHEN SUM(status = '$order_delivered') > 0 THEN '$order_partially_delivered'
                WHEN SUM(status = '$order_processing') > 0 THEN '$order_processing'
                ELSE '$order_pending'
            END AS order_status
        ")->value('order_status');
        
        return $orderStatus;
    }

    public function getIsPayedAttribute()
    {

        /*
        const Pending = 'pending';
        const PartialPayped = 'partial_payed';
        const Payed = 'payed';
        */
        $i = 10;
        return true;
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
