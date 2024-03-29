<?php

namespace App\Models;

use Carbon\Carbon;

class Instance extends BaseModel
{
    protected $table = 'Instance';

    protected $dates = ['published_at'];

    protected $fillable = [
        'title',
        'description',
        'overview',
        'price',
        'is_archived',
        'published_at',
        'id__Thing',
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

    public function thing()
    {
        return $this->belongsTo(Thing::class, 'id__Thing');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'id__Instance');
    }
}
