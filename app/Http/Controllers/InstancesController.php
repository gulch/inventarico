<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use App\Models\Operation;

class InstancesController extends Controller
{
    /* TODO: remove later */
    /* public function generate()
    {
        $operations = Operation::query()
            ->with('item')
            ->where('id__OperationType', 2)
            ->get();

        foreach ($operations as $operation) {

            $instance = new Instance();

            //$instance->id = $operation->item->id;

            $instance->title = $operation->item->title;
            $instance->price = $operation->price;
            $instance->is_archived = $operation->item->is_archived;
            $instance->id__Thing = $operation->item->id;
            $instance->id__User = $operation->item->id__User;
            $instance->created_at = $operation->created_at;

            $instance->save();

            Operation::find($operation->id)->update([
                'id__Instance' => $instance->id,
            ]);

            Operation::where('id__Item', $operation->item->id)
            ->where('id__OperationType', '<>', 2)
            ->update([
                'id__Instance' => $instance->id,
            ]);

            echo 'Instance created: ' . $instance->title;
            echo PHP_EOL;
        }
    } */
}
