<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectComment extends Model
{
    protected $fillable = [
        'project_id',
        'file',
        'comment',
        'comment_by',
        'parent',
    ];

    public function commentUser()
    {
        return $this->hasOne('App\User', 'id', 'comment_by');
    }

    public function subComment()
    {
        return $this->hasMany('App\ProjectComment', 'parent', 'id');
    }
}
