<?php

namespace App\Models;

class Photo extends BaseModel
{
    protected $table = 'Photo';

    protected $fillable = [
        'path',
        'description'
    ];

    public function operations()
    {
        return $this->belongsToMany(Operation::class, 'Operation_Photo', 'id__Photo', 'id__Operation');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'id__Photo');
    }
}
