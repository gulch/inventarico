<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $id__User
 */
final class OperationType extends Model
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

    /**
     * @return HasMany<Operation>
     */
    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'id__OperationType');
    }
}
