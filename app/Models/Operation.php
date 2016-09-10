<?php

namespace App\Models;

class Operation extends BaseModel
{
    protected $table = 'Operation';

    protected $fillable = [
        'condition',
        'price',
        'note',
        'id__OperationType'
    ];

    /* -------------- Relations -------------- */

    public function type()
    {
        return $this->belongsTo(OperationType::class, 'id__OperationType');
    }
}
