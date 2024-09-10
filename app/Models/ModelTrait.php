<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait ModelTrait
{
    /* TODO: remove unused */
    /* public static function getTableName(): string
    {
        return (new static())->getTable();
    } */

    public function setUserId(?int $id = null): void
    {
        if (null === $id) {
            $id = auth()->user()->id;
        }
        $this->id__User = $id;
    }

    /**
     * @param Builder<Model> $query
     */
    public function scopeOfCurrentUser(Builder $query): void
    {
        $query->where('id__User', auth()->user()->id);
    }

}
