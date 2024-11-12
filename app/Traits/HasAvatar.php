<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Laravolt\Avatar\Facade as Avatar;
use Throwable;

trait HasAvatar
{
    public static function bootHasAvatar()
    {
        static::saving(function ($model) {
            if(Schema::hasColumn($model->getTable(), 'avatar')){
                $avatar = null;

                try {
                    $width = config('laravolt.avatar.width');
                    $url = Avatar::create($model->email)->toGravatar(['d' => '404', 's' => $width]);
                    $image = file_get_contents($url);
                    if ($image !== false){
                        $avatar = 'data:image/jpg;base64,'.base64_encode($image);
                    }
                } catch (Throwable $e) {
                    $avatar = Avatar::create(implode(' ', array_filter([$model->name, $model->surname])))->toBase64();
                }
        
                $model->avatar = $avatar;
            }
        });
    }
}