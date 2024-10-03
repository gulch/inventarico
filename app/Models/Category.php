<?php

declare(strict_types=1);

namespace App\Models;

use Franzose\ClosureTable\Models\Entity;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $id__User
 */
final class Category extends Entity
{
    use ModelTrait;

    public $timestamps = true;

    protected $table = 'Category';

    /* ClosureTable model instance. */
    protected $closure = CategoryClosure::class;

    protected $fillable = [
        'title',
    ];

    /* -------------- Relations -------------- */

    /**
     * @return HasMany<Thing>
     */
    public function things(): HasMany
    {
        return $this->hasMany(Thing::class, 'id__Category');
    }
}
