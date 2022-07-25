<?php

namespace App\Models;

class Instance extends BaseModel
{
    protected $table = 'Instance';

    protected $fillable = [
        'title',
        'description',
        'overview',

        'price',

        'is_archived',

        'id__Thing',
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

    public function thing()
    {
        return $this->belongsTo(Thing::class, 'id__Thing');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'id__Instance');
    }
}
