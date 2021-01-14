<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Follower;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
        'name', 'introduction', 'id', 'userthumbnail', 'products', 'follows', 'followers'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany('App\Product')->orderBy('created_at', 'asc');
    }

    public function userthumbnail()
    {
        return $this->hasOne('App\Userthumbnail');
    }

    public function likes()
    {
        return $this->hasMany('App\Like')->orderBy('created_at', 'asc');
    }

    public function follows()
    {
        return $this->hasMany('App\Follower')->orderBy('created_at', 'asc');
    }

    public function followers()
    {
        return $this->hasMany('App\Follower', 'followee_id')->orderBy('created_at', 'asc');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification')->orderBy('created_at', 'asc');
    }

    public function materialproducts()
    {
        return $this->hasMany('App\Materialproduct')->orderBy('created_at', 'asc');
    }
}
