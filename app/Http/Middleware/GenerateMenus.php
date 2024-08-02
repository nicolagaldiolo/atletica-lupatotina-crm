<?php

namespace App\Http\Middleware;

use App\Enums\AthletePermission;
use App\Enums\RacePermission;
use App\Enums\Roles;
use Closure;
use Illuminate\Support\Facades\Auth;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {
            
            // Dashboard
            $menu->add('<i class="nav-icon fa-solid fa-cubes"></i> '.__('Dashboard'), [
                'route' => 'dashboard',
                'class' => 'nav-item',
            ])->data([
                'order' => 1,
                'activematches' => 'dashboard*',
                'permission' => ['view_backend'],
            ])->link->attr([
                'class' => 'nav-link',
            ]);

            // Separator
            $menu->add(__('Gestione società'), [
                'class' => 'nav-title',
            ])->data([
                'order' => 105,
                'permission' => [AthletePermission::ListAthletes, RacePermission::ListRaces],
            ]);
            
            // Athletes
            $menu->add('<i class="nav-icon fas fa-running"></i> ' . __('Tesserati'), [
                'route' => 'athletes.index',
                'class' => 'nav-item',
            ])->data([
                'order' => 110,
                'activematches' => 'athletes*',
                'permission' => [AthletePermission::ListAthletes],
            ])->link->attr([
                'class' => 'nav-link',
            ]);

            // Races
            $menu->add('<i class="nav-icon fa-solid fa-flag-checkered"></i> ' . __('Elenco gare'), [
                'route' => 'races.index',
                'class' => 'nav-item',
            ])->data([
                'order' => 101,
                'activematches' => 'races*',
                'permission' => [RacePermission::ListRaces],
            ])->link->attr([
                'class' => 'nav-link',
            ]);

            // Archive
            $menu->add('<i class="nav-icon fas fa-download"></i> ' . __('Situazione soci'), [
                'route' => 'reports.athletes',
                'class' => 'nav-item',
            ])->data([
                'order' => 102,
                'activematches' => 'reports*',
                'permission' => [RacePermission::ListRaces],
            ])->link->attr([
                'class' => 'nav-link',
            ]);

            $menu->add(__('Registrazioni'), [
                'class' => 'nav-title',
            ])->data([
                'order' => 105,
                'permission' => ['edit_settings', 'view_backups', 'view_users', 'view_roles', 'view_logs'],
            ]);

            // Races
            $menu->add('<i class="nav-icon fas fa-coins"></i> ' . __('Pagamenti'), [
                'route' => 'payments.create',
                'class' => 'nav-item',
            ])->data([
                'order' => 101,
                'activematches' => 'races*',
                'permission' => [],
            ])->link->attr([
                'class' => 'nav-link',
            ]);

            // Races
            $menu->add('<i class="nav-icon fas fa-file-contract"></i> ' . __('Iscrizioni'), [
                'route' => 'races.subscription.create',
                'class' => 'nav-item',
            ])->data([
                'order' => 101,
                'activematches' => 'races*',
                'permission' => [],
            ])->link->attr([
                'class' => 'nav-link',
            ]);

            // Separator: Access Management
            $menu->add('Amministrazione', [
                'class' => 'nav-title',
            ])->data([
                'order' => 120,
                'permission' => ['edit_settings', 'view_backups', 'view_users', 'view_roles', 'view_logs'],
            ]);

            $menu->add('<i class="nav-icon fa-solid fa-user-group"></i> Users', [
                'route' => 'users.index',
                'class' => 'nav-item',
            ])->data([
                'order' => 160,
                'activematches' => 'users*',
                'permission' => ['view_users'],
            ])->link->attr([
                'class' => 'nav-link',
            ]);

            $menu->add('<i class="nav-icon fa-solid fa-user-shield"></i> Roles', [
                'route' => 'roles.index',
                'class' => 'nav-item',
            ])->data([
                'order' => 170,
                'activematches' => 'roles*',
                'permission' => ['view_roles'],
            ])->link->attr([
                'class' => 'nav-link',
            ]);

            
            // Separator: Access Management
            $menu->add('Developer', [
                'class' => 'nav-title',
            ])->data([
                'order' => 120,
                'permission' => ['edit_settings', 'view_backups', 'view_users', 'view_roles', 'view_logs'],
            ]);

            // Log Viewer
            // Log Viewer Dropdown
            $accessControl = $menu->add('<i class="nav-icon fa-solid fa-list-check"></i> Log Viewer', [
                'class' => 'nav-group',
            ])
                ->data([
                    'order' => 180,
                    'activematches' => [
                        'log-viewer*',
                    ],
                    'permission' => ['view_logs'],
                ]);
            $accessControl->link->attr([
                'class' => 'nav-link nav-group-toggle',
                'href' => '#',
            ]);

            // Submenu: Log Viewer Dashboard
            $accessControl->add('<i class="nav-icon fa-solid fa-list"></i> Dashboard', [
                'route' => 'log-viewer::dashboard',
                'class' => 'nav-item',
            ])
                ->data([
                    'order' => 190,
                    'activematches' => 'log-viewer',
                ])
                ->link->attr([
                    'class' => 'nav-link',
                ]);

            // Submenu: Log Viewer Logs by Days
            $accessControl->add('<i class="nav-icon fa-solid fa-list-ol"></i> Logs by Days', [
                'route' => 'log-viewer::logs.list',
                'class' => 'nav-item',
            ])
                ->data([
                    'order' => 200,
                    'activematches' => 'log-viewer/logs*',
                ])
                ->link->attr([
                    'class' => 'nav-link',
                ]);

            // Access Permission Check
            $menu->filter(function ($item) {
                if ($item->data('permission')) {
                    if (auth()->check()) {
                        if (auth()->user()->hasRole(Roles::SuperAdmin)) {
                            return true;
                        }
                        if (auth()->user()->hasAnyPermission($item->data('permission'))) {
                            return true;
                        }
                    }

                    return false;
                }

                return true;
            });

            // Set Active Menu
            $menu->filter(function ($item) {
                if ($item->activematches) {
                    $activematches = is_string($item->activematches) ? [$item->activematches] : $item->activematches;
                    foreach ($activematches as $pattern) {
                        if (request()->is($pattern)) {
                            $item->active();
                            $item->link->active();
                            if ($item->hasParent()) {
                                $item->parent()->active();
                            }
                        }
                    }
                }

                return true;
            });
            
        });//->sortBy('order');

        return $next($request);
    }
}
