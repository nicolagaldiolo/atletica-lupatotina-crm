<?php

namespace App\Models;

use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
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

    protected static function newFactory(): Factory
    {
        return CertificateFactory::new();
    }

    public function Athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }
}
