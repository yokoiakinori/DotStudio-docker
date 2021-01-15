<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;
    protected $guarded = array(
        'id',
        'user_id'
    );

    protected $appends = [
        'likes_count', 'liked_by_user',
    ];

    protected $visible = [
        'id', 'user', 'productname', 'alldot', 'linedot', 'colors', 'uniquekey', 'comments', 'likes_count', 'liked_by_user', 'producttags', 'usedmaterial', 'ispublished', 'countview'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }

    public function producttags()
    {
        return $this->hasMany('App\Producttag');
    }

    public function likes()
    {
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    public function materialproducts()
    {
        return $this->belongsToMany('App\User', 'materialproducts')->withTimestamps();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }

    public function getLikedByUserAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->likes->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }

    public function shouldBeSearchable()
    {
        return $this->ispublished;
    }
}
