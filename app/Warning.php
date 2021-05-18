<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    protected $fillable = [
        'warning_to',
        'warning_by',
        'subject',
        'warning_date',
        'description',
        'created_by',
    ];


    public function employee()
    {
        return $this->hasOne('App\User', 'id', 'employee_id');
    }

    public function warningTo($warningto)
    {
        return User::where('id', $warningto)->first();
    }

    public function warningBy($warningby)
    {
        return User::where('id', $warningby)->first();
    }
}
