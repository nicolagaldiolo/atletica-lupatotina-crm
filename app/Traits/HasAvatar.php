<?php

namespace App\Traits;

use Laravolt\Avatar\Facade as Avatar;
use Throwable;

trait HasAvatar
{
    public static function bootHasAvatar()
    {
        static::saving(function ($model) {
            $avatar = null;

            try {
                $url = Avatar::create($model->email)->toGravatar(['d' => '404']);
                $image = file_get_contents($url);
                if ($image !== false){
                    $avatar = 'data:image/jpg;base64,'.base64_encode($image);
                }
            } catch (Throwable $e) {
                $avatar = Avatar::create(implode(' ', [$model->name, $model->surname]))->toBase64();
            }
    
            $model->avatar = $avatar;

        });
    }
}