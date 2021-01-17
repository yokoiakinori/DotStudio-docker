<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $visible = [
        'id', 'created_at', 'message', 'checked'
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("Y年m月d日");
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
