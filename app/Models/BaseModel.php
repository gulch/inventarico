<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class BaseModel extends Eloquent
{
    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function setUserId($id = null)
    {
        if (null === $id) {
            $id = auth()->user()->id;
        }
        $this->id__User = $id;
    }
}