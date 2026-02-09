<?php

namespace App\Models;

use App\Traits\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends Model
{
    use SoftDeletes;
    use Owner;
    
    protected $fillable = [
        'name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function orderRows(): HasMany
    {
        return $this->hasMany(OrderRow::class);
    }
}
