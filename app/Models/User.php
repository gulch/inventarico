<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'User';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /* -------------- Relations -------------- */

    public function items()
    {
        return $this->hasMany(Thing::class, 'id__User');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'id__User');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'id__User');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'id__User');
    }

    public function operationTypes()
    {
        return $this->hasMany(OperationType::class, 'id__User');
    }
}
