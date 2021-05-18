<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function stages()
    {
        return $this->hasMany('App\DealStage', 'pipeline_id', 'id')->where('created_by', '=', \Auth::user()->creatorId())->orderBy('order');
    }

    public function leadStages()
    {
        return $this->hasMany('App\LeadStage', 'pipeline_id', 'id')
                    ->where('created_by', '=', \Auth::user()->creatorId())->orderBy('order');

    }
}
