<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Photo extends Eloquent
{
    use ModelTrait;

    protected $table = 'Photo';

    protected $fillable = [
        'path',
        'description',
    ];

    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class, 'Operation_Photo', 'id__Photo', 'id__Operation');
    }

    public function things(): HasMany
    {
        return $this->hasMany(Thing::class, 'id__Photo');
    }
}
