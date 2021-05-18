<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectMilestone extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'status',
        'cost',
        'due_date',
        'summary',
    ];
}
