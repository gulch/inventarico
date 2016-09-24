<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\OperationType;

class OperationTypesController extends Controller
{
    public function index()
    {
        $data = [
            'operationTypes' => OperationType::ofCurrentUser()->latest()->paginate(24)
        ];

        return view('operation-types.index', $data);
    }

    public function create()
    {
        return view('operation-types.create');
    }

    public function edit($id)
    {
        $operationType = OperationType::findOrFail($id);
        $this->ownerAccess($operationType);
        $data = [
            'operationType' => $operationType
        ];

        return view('operation-types.edit', $data);
    }

    public function store()
    {
        return $this->saveItem();
    }

    public function update($id)
    {
        return $this->saveItem($id);
    }

    public function destroy($id)
    {
        $operationType = OperationType::find($id);
        $this->ownerAccess($operationType);

        if (is_null($operationType)) {
            return json_encode(['message' => trans('app.item_not_found')]);
        } else {
            $operationType->delete();
        }

        return json_encode(['success' => 'OK']);
    }

    private function saveItem($id = null)
    {
        if (!$id) {
            $id = $this->request->get('id');
        }

        $validation = $this->validateData();

        if ($validation['success']) {
            $validation['message'] = '<i class="ui green check icon"></i>'.trans('app.saved');
            if ($this->request->get('do_redirect')) {
                $validation['redirect'] = Session::pull('url.intended', '/operation-types');
            }

            if ($id) {
                $operationType = OperationType::findOrFail($id);
                $this->ownerAccess($operationType);
            } else {
                $operationType = new OperationType;
                $operationType->setUserId();
                $operationType->save();
            }
            $operationType->update($this->request->all());
            $validation['id'] = $operationType->id;
        }

        return $this->jsonResponse($validation);
    }

    private function validateData()
    {
        $data = [];

        $v = $this->getValidationFactory()->make($this->request->all(), ['title' => 'required']);

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
}
