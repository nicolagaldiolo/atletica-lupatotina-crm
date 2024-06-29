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

    public static function flashMessage($type = 'success', $message = null)
    {
        switch ($type) {
            case 'error':
                $icon = '<i class="fas fa-exclamation-triangle fa-lg"></i>';
                $message = $message ? $message : __('Errore, qualcosa è andato storto');
                Flash::error($icon . ' ' . $message)->important();
                break;
            case 'warning':
                $icon = '<i class="fas fa-exclamation-triangle fa-lg"></i>';
                $message = $message ? $message : __('Attenzione, qualcosa è andato storto');
                Flash::warning($icon . ' ' . $message)->important();
                break;
            case 'success':
            default:
                $icon = '<i class="fas fa-thumbs-up fa-lg"></i>';
                $message = $message ? $message : __('Operazione eseguita con successo');
                Flash::success($icon . ' ' . $message)->important();
                break;
        }
    }

    public static function dateFormatted(Carbon $date = null)
    {
        if(!$date){
            return null;
        }
        return $date->format('Y-m-d');
    }
}
