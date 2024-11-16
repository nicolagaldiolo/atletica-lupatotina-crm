<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\Permissions;
use Illuminate\Support\Facades\Artisan;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        $this->authorize(Permissions::RunMaintenance);
        
        return view('backend.tasks.index');
    }

    public function exec($task)
    {
        $this->authorize(Permissions::RunMaintenance);
        
        $output = null;
        switch ($task) {
            case 'inspire':
                Artisan::call('inspire');
                $output = Artisan::output();
                break;
            case 'migrate':
                Artisan::call('migrate --force');
                $output = Artisan::output();
                break;
            case 'migrate-rollback':
                Artisan::call('migrate:rollback --force');
                $output = Artisan::output();
                break;
            case 'setup':
                Artisan::call('app:setup');
                $output = Artisan::output();
                break;
            case 'import-data':
                Artisan::call('app:import-data');
                $output = Artisan::output();
                break;
            default:
                $output = 'task non esistente';
                break;
        }
        if($output){
            Utility::flashMessage("warning", $output);
        }
        
        return redirect(route('tasks.index'));
    }
}
