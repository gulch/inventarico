<?php

namespace App\Models;

class OperationType extends BaseModel
{
    protected $table = 'OperationType';

    protected $fillable = [
        'title',
        'id__User'
    ];
}
