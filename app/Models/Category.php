<?php

declare(strict_types=1);

namespace App\Models;

use Franzose\ClosureTable\Contracts\EntityInterface;
use Franzose\ClosureTable\Models\Entity;

final class Category extends Entity implements EntityInterface
{
    use ModelTrait;

    public $timestamps = true;

    protected $table = 'Category';

    protected $closure = CategoryClosure::class;

    protected $fillable = [
        'title',
    ];

    /* -------------- Relations -------------- */

    public function things()
    {
        return $this->hasMany(Thing::class, 'id__Category');
    }
}
