<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class BaseModel extends Eloquent
{
    use ModelTrait;
}