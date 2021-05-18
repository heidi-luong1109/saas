<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadEmail extends Model
{
    protected $fillable = [
        'lead_id',
        'to',
        'subject',
        'description',
    ];
}
