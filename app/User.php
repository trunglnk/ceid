<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'username', 'avatar_url', 'active', 'role_id', 'tinh_thanh_ids',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarUrlAttribute()
    {
        if (empty($this->attributes['avatar_url'])) {
            return "/images/defaults/avatar.png";
        }

        return $this->attributes['avatar_url'];
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    protected static function boot()
    {
        parent::boot();
    }

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function setUserNameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function getUserNameAttribute($value)
    {
        return strtolower($value);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tinh_thanh_ids' => 'array',
    ];

    protected $appends = [
        'role_code'
    ];

    public function getRoleCodeAttribute()
    {
        if ($this->role_id == 1) {
            return 'sysadmin';
        } else if ($this->role_id == 2) {
            return 'admin';
        } else if ($this->role_id == 6) {
            return 'nhanvientrungtam';
        } else if ($this->role_id == 9) {
            return 'adminvaquanlydanhmuc';
        } else if ($this->role_id == 10) {
            return 'canbo';
        } else
            return 'other';
    }
}
