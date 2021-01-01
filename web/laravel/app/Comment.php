<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $visible = [
        'user','content',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
