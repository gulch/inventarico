<?php

namespace App\Models;

class Item extends BaseModel
{
    protected $table = 'Item';

    protected $fillable = [
        'title',
        'description',
        'overview',

        'is_archived',
    ];

    /* -------------- Scopes -------------- */

    public function scopeArchived($query)
    {
        return $query->where('is_archived', 1);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_archived', 0);
    }

    /* -------------- Relations -------------- */

    public function operations()
    {
        return $this->hasMany(Operation::class, 'id__Item');
    }
}
