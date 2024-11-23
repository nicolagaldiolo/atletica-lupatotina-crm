<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Traits\Owner;

class Fee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use EagerLoadPivotTrait;
    use Owner;

    protected $fillable = [
        'name',
        'expired_at',
        'amount'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expired_at' => 'datetime',
        'amount' => 'float'
    ];

    public function Race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function athletes(): BelongsToMany
    {
        return $this->belongsToMany(Athlete::class)
            ->withTimestamps()
            ->using(AthleteFee::class)
            ->as('athletefee')
            ->withPivot([
                'id',
                'voucher_id', 
                'custom_amount', 
                'payed_at', 
                'bank_transfer', 
                'cashed_by',
                'deduct_at',
                'created_by', 
                'updated_by', 
                'deleted_by'
            ]);
    }
}
