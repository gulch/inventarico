<?php

namespace App\Models;

use Carbon\Carbon;

class Thing extends BaseModel
{
    protected $table = 'Thing';

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'description',
        'overview',
        'is_archived',
        'published_at',
        'id__Photo',
        'id__Category',
    ];

    public function setPublishedAtAttribute($date)
    {
        $this->attributes['published_at'] = Carbon::createFromFormat('d.m.Y H:i', $date);
    }

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

    public function instances()
    {
        return $this->hasMany(Instance::class, 'id__Thing');
    }
}
