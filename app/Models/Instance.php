<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;

final class Instance extends BaseModel
{
    protected $table = 'Instance';

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'description',
        'overview',
        'price',
        'is_archived',
        'published_at',
        'id__Thing',
    ];

    public function setPublishedAtAttribute($date): void
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

    public function thing()
    {
        return $this->belongsTo(Thing::class, 'id__Thing');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'id__Instance');
    }
}
