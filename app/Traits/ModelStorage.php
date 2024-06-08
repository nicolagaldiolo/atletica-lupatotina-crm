<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ModelStorage
{
    public static function getStorageBasePath($prepend = null, $id = null)
    {
        $path = [];

        if($prepend){
            $path[] = $prepend;
        }

        $path[] = Str::slug(Str::plural(class_basename(new self())));
        
        if($id){
            $path[] = $id;
        }

        return implode('/', $path);
    }
}