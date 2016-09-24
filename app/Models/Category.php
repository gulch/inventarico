<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $table = 'Category';

    protected $fillable = [
        'title'
    ];

    /* -------------- Relations -------------- */

    public function items()
    {
        return $this->hasMany(Item::class, 'id__Category');
    }
}