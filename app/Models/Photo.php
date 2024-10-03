<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $id__User
 */
final class Photo extends Model
{
    use ModelTrait;

    protected $table = 'Photo';

    protected $fillable = [
        'path',
        'description',
    ];

    /**
     * @return BelongsToMany<Operation>
     */
    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class, 'Operation_Photo', 'id__Photo', 'id__Operation');
    }

    /**
     * @return HasMany<Thing>
     */
    public function things(): HasMany
    {
        return $this->hasMany(Thing::class, 'id__Photo');
    }
}
