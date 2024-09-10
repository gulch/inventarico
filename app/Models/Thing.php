<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id__Category
 * @property int $id__User
 */
final class Thing extends Eloquent
{
    use ModelTrait;

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

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class, 'id__Photo');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id__Category');
    }

    public function instances(): HasMany
    {
        return $this->hasMany(Instance::class, 'id__Thing');
    }
}
