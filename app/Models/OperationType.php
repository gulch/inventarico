<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id__User
 */
final class OperationType extends Eloquent
{
    use ModelTrait;

    public const KIND_OF = [
        'neutral',
        'expenditure',
        'profitable',
    ];

    protected $table = 'OperationType';

    protected $fillable = [
        'title',
        'kind',
        'id__User',
    ];

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'id__OperationType');
    }
}
