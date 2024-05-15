<?php

namespace App\Classes;

use App\Models\Permission;
use Carbon\Carbon;
use Laracasts\Flash\Flash;

class Utility
{
    public static function manageDbPermissions(array $permissions, $down=false)
    {
        collect($permissions)->each(function ($permission) use ($down) {
            if($down){
                Permission::where('name', $permission)->delete();
            }else{
                Permission::firstOrCreate(['name' => $permission]);
            }
        });
    }

    public static function flashSuccess()
    {
        Flash::success(__('Operazione eseguita con successo'))->important();
    }

    public static function dateFormatted(Carbon $date = null)
    {
        if(!$date){
            return null;
        }
        return $date->format('Y-m-d');
    }
}
