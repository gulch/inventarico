<?php

namespace App\Models;

class Photo extends BaseModel
{
    protected $table = 'Photo';

    protected $fillable = [
        'path',
        'description'
    ];
}
