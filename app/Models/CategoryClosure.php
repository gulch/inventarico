<?php

namespace App\Models;

use Franzose\ClosureTable\Models\ClosureTable;
use Franzose\ClosureTable\Contracts\ClosureTableInterface;

class CategoryClosure extends ClosureTable implements ClosureTableInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CategoryClosure';
}
