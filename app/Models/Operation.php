<?php

namespace App\Models;

use Carbon\Carbon;

class Operation extends BaseModel
{
    protected $table = 'Operation';
    protected $dates = ['operated_at'];

    protected $fillable = [
        'operated_at',
        'condition',
        'price',
        'currency',
        'note',
        'id__Item',
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

    public function item()
    {
        return $this->belongsTo(Item::class, 'id__Item');
    }

    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'Operation_Photo', 'id__Operation', 'id__Photo');
    }
}
