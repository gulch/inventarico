<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id__User
 */
final class Instance extends Eloquent
{
    use ModelTrait;

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

    public function thing(): BelongsTo
    {
        return $this->belongsTo(Thing::class, 'id__Thing');
    }

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'id__Instance');
    }
}
