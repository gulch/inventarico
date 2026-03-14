<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait ModelTrait
{
    public function setUserId(?int $id = null): void
    {
        if (null === $id) {
            /** @var User $user */
            $user = Auth::user();
            $id = $user->id;
        }
        $this->id__User = $id;
    }

    /**
     * @param  Builder<Model>  $query
     */
    public function scopeOfCurrentUser(Builder $query): void
    {
        /** @var User $user */
        $user = Auth::user();
        $query->where('id__User', $user->id);
    }
}
