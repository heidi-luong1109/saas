<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'from',
        'slug',
        'created_by',
    ];

    public function template()
    {
        return $this->hasOne('App\UserEmailTemplate', 'template_id', 'id')->where('user_id', '=', \Auth::user()->id);
    }
}
