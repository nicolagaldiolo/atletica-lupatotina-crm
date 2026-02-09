<?php

namespace App\Models;

use App\Traits\ModelStorage;
use App\Traits\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;
    use Owner;
    use ModelStorage;

    protected $fillable = [
        'name',
        'price',
        'variants',
        'is_active'
    ];

    protected $appends = [
        'variants_available'
    ];

    protected $casts = [
        'price' => 'float',
        'variants' => 'array',
        'is_active' => 'boolean'
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

    public function getVariantsAvailableAttribute()
    {
        return collect($this->variants)->filter(function ($value) {
            return boolval($value['is_active'] ?? false);
        });
    }
}
