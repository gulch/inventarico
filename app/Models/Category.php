<?php

namespace App\Models;

use Franzose\ClosureTable\Models\Entity;
use Franzose\ClosureTable\Contracts\EntityInterface;

class Category extends Entity implements EntityInterface
{
    use ModelTrait;

    protected $table = 'Category';

    protected $closure = CategoryClosure::class;

    protected $fillable = [
        'title'
    ];

    public $timestamps = true;

    /* -------------- Relations -------------- */

    public function items()
    {
        return $this->hasMany(Item::class, 'id__Category');
    }
}
