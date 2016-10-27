<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Item;
use App\Models\Operation;
use App\Models\OperationType;

class OperationsController extends Controller
{
    public function index()
    {
        $operations = Operation::ofCurrentUser()
            ->with('item', 'type')
            ->orderBy('operated_at', 'desc')
            ->paginate(24);

        $data = [
            'operations' => $operations
        ];

        return view('operations.index', $data);
    }

    public function create($id__Item)
    {
        $item = Item::findOrFail($id__Item);
        $this->ownerAccess($item);
        $data = [
            'item' => $item,
            'currencies' => $this->getCurrenciesForDropDown(),
            'conditions' => $this->getConditionsForDropDown(),
            'operationTypes' => $this->getOperationTypesForDropdown()
        ];

        return view('operations.create', $data);
    }

    public function edit($id)
    {
        $operation = Operation::findOrFail($id);
        $this->ownerAccess($operation);
        $data = [
            'operation' => $operation,
            'item' => $operation->item,
            'currencies' => $this->getCurrenciesForDropDown(),
            'conditions' => $this->getConditionsForDropDown(),
            'operationTypes' => $this->getOperationTypesForDropdown()
        ];

        return view('operations.edit', $data);
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
        $operation = Operation::find($id);
        $this->ownerAccess($operation);

        if (is_null($operation)) {
            return json_encode(['message' => trans('app.item_not_found')]);
        } else {
            $operation->delete();
        }

        return json_encode(['success' => 'OK']);
    }

    private function saveItem($id = null)
    {
        $id__Item = $this->request->get('id__Item');

        if(!$id__Item) {
            $this->jsonResponse([
                'message' => trans('app.id_of_item_not_exists')
            ]);
        }

        $item = Item::find($id__Item);
        if (!$item) {
            $this->jsonResponse([
                'message' => trans('app.item_non_exists')
            ]);
        }
        $this->ownerAccess($item);

        if (!$id) {
            $id = $this->request->get('id');
        }

        $validation = $this->validateData();

        if ($validation['success']) {
            $validation['message'] = '<i class="ui green check icon"></i>' . trans('app.saved');
            if ($this->request->get('do_redirect')) {
                $validation['redirect'] = Session::pull('url.intended', '/operations');
            }

            if ($id) {
                $operation = Operation::findOrFail($id);
                $this->ownerAccess($operation);
            } else {
                $operation = new Operation;
                $operation->setUserId();
                $operation->save();
            }
            $validation['id'] = $operation->id;

            $operation_input = array_map('trim', $this->request->only([
                'id__OperationType',
                'id__Item',
                'operated_at',
                'note',
                'condition',
                'price',
                'currency'
            ]));
            $operation->update($operation_input);

            // Save operation Photos
            $operation_photos = $this->request->get('operation_photos');
            $this->syncPhotos($operation, $this->request->get('operation_photos'));
        }

        return $this->jsonResponse($validation);
    }

    private function validateData()
    {
        $data = [];

        $v = $this->getValidationFactory()->make($this->request->all(), [
            'id__OperationType' => 'required|numeric|min:1',
            'operated_at' => 'required',
            'price' => 'required|numeric',
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

    private function syncPhotos(Operation $operation, $photos)
    {
        if (is_null($photos)) {
            $photos = [];
        }
        $operation->photos()->sync($photos);
    }

    private function getOperationTypesForDropdown()
    {
        $result = ['0' => '---'] + OperationType::ofCurrentUser()->pluck('title', 'id')->all();

        return $result;
    }

    private function getCurrenciesForDropDown()
    {
        $result = [
            'UAH' => trans('app.uah'),
            'USD' => trans('app.usd'),
            'EUR' => trans('app.eur')
        ];

        return $result;
    }

    private function getConditionsForDropDown()
    {
        $result = [
            'NEW' => trans('app.new'),
            'USED' => trans('app.used')
        ];

        return $result;
    }
}
