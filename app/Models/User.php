<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'User';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* -------------- Relations -------------- */

    /**
     * @return HasMany<Thing>
     */
    public function items(): HasMany
    {
        return $this->hasMany(Thing::class, 'id__User');
    }

    /**
     * @return HasMany<Category>
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'id__User');
    }

    /**
     * @return HasMany<Photo>
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class, 'id__User');
    }

    /**
     * @return HasMany<Operation>
     */
    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'id__User');
    }

    /**
     * @return HasMany<OperationType>
     */
    public function operationTypes(): HasMany
    {
        return $this->hasMany(OperationType::class, 'id__User');
    }
}
