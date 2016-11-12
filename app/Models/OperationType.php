<?php

namespace App\Models;

class OperationType extends BaseModel
{
    protected $table = 'OperationType';

    protected $fillable = [
        'title',
        'id__User'
    ];

    public function operations()
    {
        return $this->hasMany(Operation::class, 'id__OperationType');
    }
}
