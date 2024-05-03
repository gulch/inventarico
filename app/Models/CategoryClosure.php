<?php

declare(strict_types=1);

namespace App\Models;

use Franzose\ClosureTable\Contracts\ClosureTableInterface;
use Franzose\ClosureTable\Models\ClosureTable;

final class CategoryClosure extends ClosureTable implements ClosureTableInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CategoryClosure';
}
