<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait Owner
{
    protected static function bootOwner() {
        static::creating(function ($model) {
            if(Auth::id() && (Schema::hasColumn($model->getTable(), 'created_by'))){
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if(Auth::id() && (Schema::hasColumn($model->getTable(), 'updated_by'))){
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if(Auth::id() && (Schema::hasColumn($model->getTable(), 'deleted_by'))){
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}