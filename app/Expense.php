<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'user',
        'project',
        'attachment',
        'description',
        'created_by',
    ];

    public function users()
    {
        return $this->hasOne('App\User', 'id', 'user');
    }

    public function projects()
    {
        return $this->hasOne('App\Project', 'id', 'project');
    }
}
