<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'profile'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['type', 'profile_src'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function files()
    {
        return $this->belongsToMany(File::class)
                    ->withPivot('payment_id');
    }

    public function memberships()
    {
        return $this->belongsToMany(Membership::class)
                    ->withPivot(['payment_id', 'expire_at'])
                    ->withTimestamps();
    }


    public function getCurrentMembershipAttribute()
    {
        return $this->memberships()
            ->where('membership_user.expire_at', '>', now())
            ->orderBy('membership_user.created_at', 'desc')
            ->first();
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function getTypeAttribute()
    {
        return $this->is_admin ? 'ادمین' : 'کاربر عادی';
    }

    public function getProfileSrcAttribute()
    {
        return $this->profile ? "/profiles/{$this->profile}" : "/profiles/avatar.png";
    }
}
