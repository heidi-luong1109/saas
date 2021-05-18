<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'user_id',
        'pipeline_id',
        'stage_id',
        'sources',
        'products',
        'notes',
        'labels',
        'order',
        'created_by',
        'is_active',
        'date',
    ];


    public function stage()
    {
        return $this->hasOne('App\LeadStage', 'id', 'stage_id');
    }

    public function files()
    {
        return $this->hasMany('App\LeadFile', 'lead_id', 'id');
    }

    public function pipeline()
    {
        return $this->hasOne('App\Pipeline', 'id', 'pipeline_id');
    }

    public function items()
    {

        if($this->items)
        {
            return Item::whereIn('id', explode(',', $this->items))->get();
        }

        return [];
    }

    public function sources()
    {
        if($this->sources)
        {
            return Source::whereIn('id', explode(',', $this->sources))->get();
        }

        return [];
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_leads', 'lead_id', 'user_id');
    }

    public function activities()
    {
        return $this->hasMany('App\LeadActivityLog', 'lead_id', 'id')->orderBy('id', 'desc');
    }

    public function discussions()
    {
        return $this->hasMany('App\LeadDiscussions', 'lead_id', 'id')->orderBy('id', 'desc');
    }

    public function calls()
    {
        return $this->hasMany('App\LeadCall', 'lead_id', 'id');
    }

    public function emails()
    {
        return $this->hasMany('App\LeadEmail', 'lead_id', 'id')->orderByDesc('id');
    }

    public function labels()
    {
        if($this->labels)
        {
            return Label::whereIn('id', explode(',', $this->labels))->get();
        }

        return false;
    }

    public static function getDealSummary($deals)
    {
        $total = 0;

        foreach($deals as $deal)
        {
            $total += $deal->price;
        }

        return \Auth::user()->priceFormat($total);
    }
}
