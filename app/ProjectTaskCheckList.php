<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskCheckList extends Model
{
    protected $fillable = [
        'name',
        'task_id',
        'created_by',
        'status',
    ];
}
