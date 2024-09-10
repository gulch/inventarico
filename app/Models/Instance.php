<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id__User
 */
final class Instance extends Model
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
     * @return BelongsTo<Thing, $this>
     */
    public function thing(): BelongsTo
    {
        return $this->belongsTo(Thing::class, 'id__Thing');
    }

    /**
     * @return HasMany<Operation>
     */
    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'id__Instance');
    }
}
