<?php

namespace App\Models;

class OperationType extends BaseModel
{
    public const KIND_OF = [
        'neutral',
        'expenditure',
        'profitable',
    ];

    protected $table = 'OperationType';

    protected $fillable = [
        'title',
        'kind',
        'id__User'
    ];

    public function operations()
    {
        return $this->hasMany(Operation::class, 'id__OperationType');
    }
}
