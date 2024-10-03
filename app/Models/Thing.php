<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $id__Category
 * @property int $id__User
 */
final class Thing extends Model
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

    public function setPublishedAtAttribute(string $date): void
    {
        $this->attributes['published_at'] = Carbon::createFromFormat('d.m.Y H:i', $date);
    }

    /* -------------- Scopes -------------- */

    /**
     * @param Builder<Model> $query
     */
    public function scopeArchived(Builder $query): void
    {
        $query->where('is_archived', 1);
    }

    /**
     * @param Builder<Model> $query
     */
    public function scopeAvailable(Builder $query): void
    {
        $query->where('is_archived', 0);
    }

    /* -------------- Relations -------------- */

    /**
     * @return BelongsTo<Photo, $this>
     */
    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class, 'id__Photo');
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id__Category');
    }

    /**
     * @return HasMany<Instance>
     */
    public function instances(): HasMany
    {
        return $this->hasMany(Instance::class, 'id__Thing');
    }
}
