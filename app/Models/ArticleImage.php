<?php

namespace App\Models;

use App\Traits\ModelStorage;
use App\Traits\Owner;
use App\Traits\UserStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Intervention\Image\ImageManagerStatic;

class ArticleImage extends Model
{
    use Owner;
    use ModelStorage;

    protected $fillable = [
        'article_id',
        'image',
        'position',
        'is_default',
        'is_disabled'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_disabled' => 'boolean'
    ];

    protected $appends = [
        'info',
        'public_url'
    ];

    protected static function booted()
    {

        ArticleImage::saving(function ($model) {
            // Al salvataggio dell'immagine se non è settata come default mi assicuro che ce ne sia almeno una, altrimenti la setto come default
            if($model->is_default){
                ArticleImage::where('article_id', $model->article_id)->whereNot('id', $model->id)->where('is_default', true)->each(function($image){
                    $image->is_default = false;
                    $image->saveQuietly();
                });
            }else{
                $defaultImage = ArticleImage::where('article_id', $model->article_id)->where('is_default', true)->count();
                if(!$defaultImage){
                    $model->is_default = true;
                }
            }
        });

        ArticleImage::creating(function ($model) {
            $model->position = ArticleImage::where('article_id', $model->article_id)->max('position') + 1;
        });

        ArticleImage::deleting(function ($model) {
            // Mi assicuro di settare almeno un immagine di default
            if($model->is_default){
                $firstImage = ArticleImage::where('article_id', $model->article_id)->whereNot('id', $model->id)->orderBy('position')->first();
                if($firstImage){
                    $firstImage->is_default = true;
                    $firstImage->saveQuietly();
                }
            }
        });

        ArticleImage::deleted(function ($model) {
            // elimino il file
            Storage::delete($model->image);
        });
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function setImageAttribute($image)
    {
        if ($image) {
            $basePath = self::getStorageBasePath(Article::getStorageBasePath(null, $this->article_id));
            $result = handleUploadedFile($basePath, $image, $this->getOriginal('image'));
            if($result){
                $this->attributes['image'] = $result;
            }else{
                throw new \Exception('Impossibile salvare il file');
            }
        }
    }

    public function getPublicUrlAttribute()
    {
        $path = null;
        if($this->image && Storage::exists($this->image)){
            $path = asset($this->image);
        }

        return $path;
    }

    public function getInfoAttribute()
    {
        if($this->image && Storage::exists($this->image)){
            return [
                'id' => $this->id,
                'size' => Storage::size($this->image),
                'url' => route('articles.destroyImage', [$this->article_id, $this->id]),
                'is_default' => $this->is_default,
                'set_default_url' => route('articles.defaultImage', [$this->article_id, $this->id]),
                'is_disabled' => $this->is_disabled,
                'set_disable_url' => route('articles.disableImage', [$this->article_id, $this->id])
            ];
        }
    }

    public function scopeDefault(Builder $query): void
    {
        $query->where('is_default', true);
    }
}