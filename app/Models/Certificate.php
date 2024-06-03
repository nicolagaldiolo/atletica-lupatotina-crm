<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'document',
        'expires_on'
    ];

    protected $casts = [
        'expires_on' => 'datetime'
    ];

    public function Athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }
}
