<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskComment extends Model
{
    protected $fillable = [
        'comment',
        'task_id',
        'created_by',
        'user_type',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
}
