<?php

namespace App\Models;

use App\Traits\Owner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Owner;

    protected $fillable = [
        'name',
        'price',
        'variants',
        'is_active'
    ];

    protected $casts = [
        'price' => 'float',
        'variants' => 'array',
        'is_active' => 'boolean',
    ];
}
