<?php

namespace App\Models;

use App\Traits\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderRow extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Owner;

    protected $fillable = [
        'article_id',
        'variant',
        'quantity',
        'amount',
        'total_amount',
        'status'
    ];

    protected $appends = [
        'is_payed'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'float',
        'total_amount' => 'float'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function getIsPayedAttribute()
    {
        return $this->transactions->sum('amount') >= $this->total_amount;
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
