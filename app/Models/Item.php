<?php

namespace App\Models;

class Item extends BaseModel
{
    protected $table = 'Item';

    protected $fillable = [
        'title',
        'description',
        'overview',
        'id__Photo',
        'id__Category'
    ];

    /* -------------- Relations -------------- */

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'id__Photo');
    }

    public function operations()
    {
        return $this->belongsToMany(Operation::class, 'Item_Operation', 'id__Item', 'id__Operation');
    }
}
