<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use App\Models\Thing;

class InstancesController extends Controller
{
    public function create(int $id__Thing)
    {
        $thing = Thing::findOrFail($id__Thing);

        $this->ownerAccess($thing);

        $data = [
            'instance' => null,
            'thing' => $thing,
        ];

        session()->put('url.intended', url()->previous());

        return view('instances.create', $data);
    }

    public function edit(int $id)
    {
        $instance = Instance::findOrFail($id);

        $this->ownerAccess($instance);

        session()->put('url.intended', url()->previous());

        $data = [
            'instance' => $instance,
            'thing' => $instance->thing,
        ];

        return view('instances.edit', $data);
    }

    public function store()
    {
        return $this->saveInstance();
    }

    public function update(int $id)
    {
        return $this->saveInstance($id);
    }

    public function destroy(int $id)
    {
        $instance = Instance::findOrFail($id);

        $this->ownerAccess($instance);

        if (is_null($instance)) {
            return json_encode(['message' => trans('app.item_not_found')]);
        }

        // Unsync photos from operations
        if ($instance->operations) {
            foreach ($instance->operations as $o) {
                $o->photos()->sync([]);
            }
            // Delete operations
            $instance->operations()->delete();
        }

        // Delete item
        $instance->delete();

        return json_encode(['success' => 'OK']);
    }

    private function saveInstance(?int $id = null)
    {
        if (!$id) {
            $id = $this->request->get('id');
        }

        $validation = $this->validateData();

        if ($validation['success']) {
            $validation['message'] = '<i class="ui green check icon"></i>' . trans('app.saved');
            if ($this->request->get('do_redirect')) {
                $validation['redirect'] = session()->pull('url.intended', '/instances');
            }

            if ($id) {
                $instance = Instance::findOrFail($id);
                $this->ownerAccess($instance);
            } else {
                $instance = new Instance;
                $instance->setUserId();
                $instance->save();
            }

            $validation['id'] = $instance->id;

            $instance_input = \array_map(
                'trim',
                $this->request->only([
                    'title',
                    'description',
                    'is_archived',
                    'published_at',
                    'price',
                    'id__Thing',
                ])
            );

            $overview = $this->getOverview();

            $instance_input = \array_merge($instance_input, compact('overview'));

            $instance_input = $this->setCheckboxesValues($instance_input);

            $instance->update($instance_input);
        }

        return $this->jsonResponse($validation);
    }

    private function getOverview()
    {
        $title = $this->request->get('o_title');
        $description = $this->request->get('o_description');
        $value = $this->request->get('o_value');

        $overview = [];

        if (\is_array($title)) {
            $count = \sizeof($title);
            for ($i = 0; $i < $count; ++$i) {
                if ($value[$i] && $title[$i]) {
                    $o = [];
                    $o['title'] = trim($title[$i]);
                    $o['description'] = trim($description[$i]);
                    $o['value'] = trim($value[$i]);
                    $o['order'] = $i + 1;
                    $overview[] = $o;
                }
            }
        }

        return json_encode($overview, JSON_UNESCAPED_UNICODE);
    }

    private function validateData()
    {
        $data = [];

        $v = $this->getValidationFactory()->make($this->request->all(), [
            'title' => 'required',
            'id__Thing' => 'required|numeric|min:1',
        ]);

        if ($v->fails()) {
            $data['success'] = 0;
            $data['message'] = '<ul>';
            $messages = $v->errors()->all();
            foreach ($messages as $m) {
                $data['message'] .= '<li>' . $m . '</li>';
            }
            $data['message'] .= '</ul>';
        } else {
            $data['success'] = 'OK';
        }

        return $data;
    }

    private function setCheckboxesValues($input)
    {
        if (!isset($input['is_archived'])) {
            $input['is_archived'] = 0;
        }

        return $input;
    }

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
            $instance->id__Instance = $operation->item->id;
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
