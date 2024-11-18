<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Enums\Permissions;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

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
        
        $output = new BufferedOutput();

        switch ($task) {
            case 'inspire':
                Artisan::call('inspire', [], $output);
                break;
            case 'migrate':
                Artisan::call('migrate --force', [], $output);
                break;
            case 'migrate-rollback':
                Artisan::call('migrate:rollback --force', [], $output);
                break;
            case 'setup':
                Artisan::call('app:setup', [], $output);
                break;
            case 'import-data':
                Artisan::call('app:import-data', [], $output);
                break;
            case 'storage-link':
                Artisan::call('storage:link', [], $output);
                break;
            default:
                $output = 'task non esistente';
                break;
        }
        
        $message = trim($output->fetch());

        if($message){
            Utility::flashMessage("warning", $message);
        }
        
        return redirect(route('tasks.index'));
    }
}
