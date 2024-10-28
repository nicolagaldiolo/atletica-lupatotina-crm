<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\CertificateStatus;
use App\Traits\ModelStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory,
    SoftDeletes,
    ModelStorage;

    protected $fillable = [
        'document',
        'is_current',
        'expires_on'
    ];

    protected $casts = [
        'is_current' => 'boolean',
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

        static::saving(function($model){
            if($model->is_current){
                Certificate::where('athlete_id', $model->athlete_id)->whereNot('id', $model->id)->update([
                    'is_current' => false
                ]);
            }
        });
    }

    public function Athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function getStatusAttribute()
    {
        if($this->id){
            
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
                'status_class' => $status_class,
                'url_download' => $this->document && Storage::exists($this->document) ? asset('storage/' . $this->document) : null
            ];
        }else{

            return [
                'date' => null,
                'date_diff' => null,
                'status' => null,
                'status_class' => null,
                'url_download' => null
            ];

        }
    }

    public function setDocumentAttribute($image)
    {
        if ($image) {
            $basePath = self::getStorageBasePath(Athlete::getStorageBasePath(null, $this->athlete_id));
            $result = handleUploadedFile($basePath, $image, $this->getOriginal('document'));
            if($result){
                $this->attributes['document'] = $result;
            }else{
                throw new \Exception('Immpossibile salvare il file');
            }
        }
    }

    public function scopeCurrent(Builder $query): void
    {
        $query->where('is_current', true);
    }

    public function scopeExpiring(Builder $query): void
    {
        $query->where('expires_on', '<=', Carbon::now()->addMonth()->endOfMonth());
    }
}
