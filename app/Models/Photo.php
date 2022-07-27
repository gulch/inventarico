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

    public function things()
    {
        return $this->hasMany(Thing::class, 'id__Photo');
    }
}
