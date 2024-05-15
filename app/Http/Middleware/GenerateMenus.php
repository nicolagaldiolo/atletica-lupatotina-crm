<?php

namespace App\Http\Middleware;

use App\Enums\AthletePermission;
use App\Enums\RacePermission;
use Closure;

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
                'route' => 'backend.dashboard',
                'class' => 'nav-item',
            ])
                ->data([
                    'order' => 1,
                    'activematches' => 'admin/dashboard*',
                ])
                ->link->attr([
                    'class' => 'nav-link',
                ]);

            // Notifications
            $menu->add('<i class="nav-icon fas fa-bell"></i> Notifiche', [
                'route' => 'backend.notifications.index',
                'class' => 'nav-item',
            ])
                ->data([
                    'order' => 99,
                    'activematches' => 'admin/notifications*',
                    'permission' => [],
                ])
                ->link->attr([
                    'class' => 'nav-link',
                ]);

// Athletes
$menu->add('<i class="nav-icon fas fa-running"></i> ' . __('Tesserati'), [
    'route' => 'backend.athletes.index',
    'class' => 'nav-item',
])->data([
    'order' => 110,
    'activematches' => 'admin/athletes*',
    'permission' => [AthletePermission::ListAthletes],
])->link->attr([
    'class' => 'nav-link',
]);

// Archive
/*$menu->add('<i class="nav-icon fas fa-running"></i> ' . __('Archivio tesserati'), [
    'route' => 'backend.athletes.trashed',
    'class' => 'nav-item',
])->data([
    'order' => 110,
    'activematches' => 'admin/athletes*',
    'permission' => [AthletePermission::RestoreAthletes],
])->link->attr([
    'class' => 'nav-link',
]);
*/


                // Races
                $menu->add('<i class="nav-icon fa-solid fa-flag-checkered"></i> ' . __('Elenco gare'), [
                    'route' => 'backend.races.index',
                    'class' => 'nav-item',
                ])->data([
                    'order' => 101,
                    'activematches' => 'admin/races*',
                    'permission' => [RacePermission::ListRaces],
                ])->link->attr([
                    'class' => 'nav-link',
                ]);

                /*
                // Archive
                $menu->add('<i class="nav-icon fa-solid fa-flag-checkered"></i> ' . __('Archivio gare'), [
                    'route' => 'backend.races.trashed',
                    'class' => 'nav-item',
                ])->data([
                    'order' => 102,
                    'activematches' => 'admin/races*',
                    'permission' => [RacePermission::ListRaces],
                ])->link->attr([
                    'class' => 'nav-link',
                ]);
                */


// Separator: Access Management
$menu->add(__('Operazioni'), [
    'class' => 'nav-title',
])->data([
    'order' => 105,
    'permission' => ['edit_settings', 'view_backups', 'view_users', 'view_roles', 'view_logs'],
]);


            // Races
            $menu->add('<i class="nav-icon fa-solid fa-flag-checkered"></i> ' . __('Iscrizioni'), [
                'route' => 'backend.races.subscription.create',
                'class' => 'nav-item',
            ])->data([
                'order' => 101,
                'activematches' => 'admin/races*',
                'permission' => [],
            ])->link->attr([
                'class' => 'nav-link',
            ]);

            // Races
            $menu->add('<i class="nav-icon fa-solid fa-flag-checkered"></i> ' . __('Pagamenti'), [
                'route' => 'backend.payments.create',
                'class' => 'nav-item',
            ])->data([
                'order' => 101,
                'activematches' => 'admin/races*',
                'permission' => [],
            ])->link->attr([
                'class' => 'nav-link',
            ]);


            // Separator: Access Management
$menu->add(__('Estrazioni'), [
    'class' => 'nav-title',
])->data([
    'order' => 105,
    'permission' => ['edit_settings', 'view_backups', 'view_users', 'view_roles', 'view_logs'],
]);

            // Archive
            $menu->add('<i class="nav-icon fa-solid fa-flag-checkered"></i> ' . __('Situazione soci'), [
                'route' => 'backend.reports.athletes',
                'class' => 'nav-item',
            ])->data([
                'order' => 102,
                'activematches' => 'admin/reports*',
                'permission' => [RacePermission::ListRaces],
            ])->link->attr([
                'class' => 'nav-link',
            ]);




                // Separator: Access Management
            $menu->add('Management', [
                'class' => 'nav-title',
            ])
                ->data([
                    'order' => 120,
                    'permission' => ['edit_settings', 'view_backups', 'view_users', 'view_roles', 'view_logs'],
                ]);

            // Settings
            $menu->add('<i class="nav-icon fas fa-cogs"></i> Settings', [
                'route' => 'backend.settings',
                'class' => 'nav-item',
            ])
                ->data([
                    'order' => 130,
                    'activematches' => 'admin/settings*',
                    'permission' => ['edit_settings'],
                ])
                ->link->attr([
                    'class' => 'nav-link',
                ]);

            // Backup
            $menu->add('<i class="nav-icon fas fa-archive"></i> Backups', [
                'route' => 'backend.backups.index',
                'class' => 'nav-item',
            ])
                ->data([
                    'order' => 140,
                    'activematches' => 'admin/backups*',
                    'permission' => ['view_backups'],
                ])
                ->link->attr([
                    'class' => 'nav-link',
                ]);

            // Access Control Dropdown
            $accessControl = $menu->add('<i class="nav-icon fa-solid fa-user-gear"></i> Access Control', [
                'class' => 'nav-group show',
            ])
                ->data([
                    'order' => 150,
                    'activematches' => [
                        'admin/users*',
                        'admin/roles*',
                    ],
                    'permission' => ['view_users', 'view_roles'],
                ]);
            $accessControl->link->attr([
                'class' => 'nav-link nav-group-toggle',
                'href' => '#',
            ]);

            // Submenu: Users
            $accessControl->add('<i class="nav-icon fa-solid fa-user-group"></i> Users', [
                'route' => 'backend.users.index',
                'class' => 'nav-item',
            ])
                ->data([
                    'order' => 160,
                    'activematches' => 'admin/users*',
                    'permission' => ['view_users'],
                ])
                ->link->attr([
                    'class' => 'nav-link',
                ]);

            // Submenu: Roles
            $accessControl->add('<i class="nav-icon fa-solid fa-user-shield"></i> Roles', [
                'route' => 'backend.roles.index',
                'class' => 'nav-item',
            ])
                ->data([
                    'order' => 170,
                    'activematches' => 'admin/roles*',
                    'permission' => ['view_roles'],
                ])
                ->link->attr([
                    'class' => 'nav-link',
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
                    'activematches' => 'admin/log-viewer',
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
                    'activematches' => 'admin/log-viewer/logs*',
                ])
                ->link->attr([
                    'class' => 'nav-link',
                ]);

            // Access Permission Check
            $menu->filter(function ($item) {
                if ($item->data('permission')) {
                    if (auth()->check()) {
                        if (auth()->user()->hasRole('super admin')) {
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
