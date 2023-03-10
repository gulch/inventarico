<?php

namespace App\Models;

use Carbon\Carbon;

class Operation extends BaseModel
{
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
        'id__OperationType'
    ];

    public function setOperatedAtAttribute($date)
    {
        $this->attributes['operated_at'] = Carbon::createFromFormat('d.m.Y H:i', $date);
    }

    /* -------------- Relations -------------- */

    public function type()
    {
        return $this->belongsTo(OperationType::class, 'id__OperationType');
    }

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'id__Instance');
    }

    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'Operation_Photo', 'id__Operation', 'id__Photo');
    }
}
