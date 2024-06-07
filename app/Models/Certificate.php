<?php

namespace App\Models;

use App\Enums\CertificateStatus;
use Carbon\Carbon;
use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Builder;
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

    protected $appends = [
        'status'
    ];

    protected static function newFactory(): Factory
    {
        return CertificateFactory::new();
    }

    protected static function booted(): void
    {
        static::addGlobalScope('expires_on', function (Builder $builder){
            $builder->orderBy('expires_on', 'desc');
        });
    }

    public function Athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function getStatusAttribute()
    {
        if(Carbon::now()->startOfDay()->greaterThan($this->expires_on->startOfDay())){
            $status = CertificateStatus::Expired;
            $status_class = 'danger';
        } elseif(Carbon::now()->startOfDay()->addMonth()->greaterThanOrEqualTo($this->expires_on->startOfDay())){
            $status = CertificateStatus::Expiring;
            $status_class = 'warning';
        } else{
            $status = CertificateStatus::Valid;
            $status_class = 'success';
        }

        return [
            'date' => $this->expires_on->format('d/m/Y'),
            'date_diff' => $this->expires_on->endOfDay()->diffForHumans(),
            'status' => $status,
            'status_class' => $status_class
        ];
    }
}
