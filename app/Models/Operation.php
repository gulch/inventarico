<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property mixed $id
 * @property mixed $id__User
 */
final class Operation extends Model
{
    use ModelTrait;

    protected $table = 'Operation';

    protected $casts = [
        'operated_at' => 'datetime',
    ];

    protected $fillable = [
        'condition',
        'currency',
        'id__Instance',
        'id__OperationType',
        'note',
        'operated_at',
        'price',
    ];

    public function setOperatedAtAttribute(string $date): void
    {
        $this->attributes['operated_at'] = Carbon::createFromFormat('d.m.Y H:i', $date);
    }

    /* -------------- Relations -------------- */

    /**
     * @return BelongsTo<OperationType, $this>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'id__OperationType');
    }

    /**
     * @return BelongsTo<Instance, $this>
     */
    public function instance(): BelongsTo
    {
        return $this->belongsTo(Instance::class, 'id__Instance');
    }

    /**
     * @return BelongsToMany<Photo>
     */
    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'Operation_Photo', 'id__Operation', 'id__Photo');
    }
}
