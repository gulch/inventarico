<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Operation extends Eloquent
{
    use ModelTrait;

    protected $table = 'Operation';

    protected $casts = [
        'operated_at' => 'datetime',
    ];

    protected $fillable = [
        'operated_at',
        'condition',
        'price',
        'currency',
        'note',
        'id__Instance',
        'id__OperationType',
    ];

    public function setOperatedAtAttribute($date): void
    {
        $this->attributes['operated_at'] = Carbon::createFromFormat('d.m.Y H:i', $date);
    }

    /* -------------- Relations -------------- */

    public function type(): BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'id__OperationType');
    }

    public function instance(): BelongsTo
    {
        return $this->belongsTo(Instance::class, 'id__Instance');
    }

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'Operation_Photo', 'id__Operation', 'id__Photo');
    }
}
