<?php

declare(strict_types=1);

namespace App\Models;

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

    public function scopeOfCurrentUser($query)
    {
        return $query->where('id__User', auth()->user()->id);
    }

}
