<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentUpload extends Model
{
    protected $fillable = [
        'name',
        'document',
        'description',
        'created_by',
    ];
}
