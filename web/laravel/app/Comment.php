<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $visible = [
        'user', 'content', 'id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
