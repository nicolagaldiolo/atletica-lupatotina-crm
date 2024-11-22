<?php

namespace App\Models;

use App\Enums\Permissions;
use App\Enums\Roles;
use App\Traits\HasAvatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;
    use HasAvatar;
    use Impersonate;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password', 
        'remember_token'
    ];

    public function athlete()
    {
        return $this->hasOne(Athlete::class);
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_NOTIFICATION_WEBHOOK');
    }

    public function scopeHandlePayments($query): void
    {
        $query->permission(Permissions::HandlePayments);
    }

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        $user = Auth::user();
        $impersonator = app('impersonate')->getImpersonatorId();
        if ($impersonator) {
            $user = User::findOrFail($impersonator);
        }

        return $user->hasRole(Roles::SuperAdmin);
    }
}
