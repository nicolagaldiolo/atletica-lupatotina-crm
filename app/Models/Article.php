<?php

namespace App\Models;

use App\Traits\ModelStorage;
use App\Traits\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Owner;
    use ModelStorage;

    protected $fillable = [
        'name',
        'price',
        'is_unlimited',
        'quantity',
        'type',
        'variants',
        'is_active'
    ];

    protected $casts = [
        'price' => 'float',
        'variants' => 'array',
        'is_active' => 'boolean',
        'is_unlimited' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(ArticleImage::class)->orderBy('position')->orderBy('id');
    }

    public function imageDefault()
    {
        return $this->hasOne(ArticleImage::class)->default()->orderBy('position')->orderBy('id');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
