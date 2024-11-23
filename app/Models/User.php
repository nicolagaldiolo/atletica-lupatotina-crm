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

    protected $appends = [
        'login_status'
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
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

    public function getLoginStatusAttribute()
    {
        return [
            'date' => $this->last_login_at ? $this->last_login_at->format('d/m/Y H:i:s'): null,
            'date_diff' => $this->last_login_at ? $this->last_login_at->diffForHumans() : null
        ];
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

    public function scopeHandlePayments($query): void
    {
        $query->permission(Permissions::HandlePayments);
    }
}
