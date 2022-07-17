<?php

namespace App\Models;

class Thing extends BaseModel
{
    protected $table = 'Thing';

    protected $fillable = [
        'title',
        'description',
        'overview',
        'is_archived',

        'id__Photo',
        'id__Category',
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

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'id__Photo');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id__Category');
    }
}
