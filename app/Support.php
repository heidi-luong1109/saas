<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = [
        'subject',
        'user',
        'priority',
        'end_date',
        'ticket_code',
        'status',
        'created_by',
    ];

    public static $priority = [
        'Low',
        'Medium',
        'High',
        'Critical',
    ];

    public function createdBy()
    {
        return $this->hasOne('App\user', 'id', 'created_by');
    }

    public static $status = [
        'Open',
        'Close',
        'On Hold',
    ];

    public function replyUnread()
    {

        if(\Auth::user()->type == 'employee')
        {
            return SupportReply:: where('support_id', $this->id)->where('is_read', 0)->where('user', '!=', \Auth::user()->id)->count('id');
        }
        else
        {
            return SupportReply:: where('support_id', $this->id)->where('is_read', 0)->count('id');
        }
    }
}
