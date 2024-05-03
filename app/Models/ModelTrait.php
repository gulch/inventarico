<?php

declare(strict_types=1);

namespace App\Models;

trait ModelTrait
{
    public static function getTableName()
    {
        return (new static())->getTable();
    }

    public function setUserId($id = null): void
    {
        if (null === $id) {
            $id = auth()->user()->id;
        }
        $this->id__User = $id;
    }

    public function scopeOfCurrentUser($query)
    {
        return $query = $query->where('id__User', auth()->user()->id);
    }
}
