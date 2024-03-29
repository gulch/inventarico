<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use App\Models\Item;
use App\Models\Operation;
use App\Models\OperationType;

use function view, trans, session, url;

class OperationsController extends Controller
{
    private const PAGINATE_COUNT = 25;

    public function index()
    {
        $operations = Operation::ofCurrentUser()
            ->with([
                'type',
                'instance' => function ($query) {
                    $query->with([
                        'thing' => function ($query) {
                            $query->with('photo');
                        }
                    ]);
                }
            ]);

        $operationtype = $this->request->input('operationtype');

        if ($operationtype) {
            $operations->where('id__OperationType', $operationtype);
        }

        $sort = $this->request->input('sort');
        switch ($sort) {
            case 'operation_date_asc':
                $operations->orderBy('operated_at');
                break;
            case 'created_asc':
                $operations->orderBy('created_at');
                break;
            case 'created_desc':
                $operations->orderBy('created_at', 'desc');
                break;
            case 'operation_date_desc':
            default:
                $operations->orderBy('operated_at', 'desc');
        }

        $operations = $operations->paginate(self::PAGINATE_COUNT);

        $data = [
            'operations' => $operations,
            'operationTypes' => ['0' => '-- '.trans('app.all').' --'] + OperationType::pluck('title', 'id')->all()
        ];

        return view('operations.index', $data);
    }

    public function create(int $id__Instance)
    {
        $instance = Instance::findOrFail($id__Instance);

        $this->ownerAccess($instance);

        session()->put('url.intended', url()->previous());

        $data = [
            'instance' => $instance,
            'currencies' => $this->getCurrenciesForDropDown(),
            'conditions' => $this->getConditionsForDropDown(),
            'operationTypes' => $this->getOperationTypesForDropdown()
        ];

        return view('operations.create', $data);
    }

    public function edit(int $id)
    {
        $operation = Operation::findOrFail($id);

        $this->ownerAccess($operation);

        session()->put('url.intended', url()->previous());

        $data = [
            'operation' => $operation,
            'instance' => $operation->instance,
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

    public function destroy(int $id)
    {
        $operation = Operation::findOrFail($id);

        $this->ownerAccess($operation);

        // Unsync photos
        $operation->photos()->sync([]);

        // Delete operation
        $operation->delete();

        return $this->jsonResponse(['success' => 'OK']);
    }

    private function saveItem(?int $id = null)
    {
        $id__Instance = $this->request->get('id__Instance');

        if (!$id__Instance) {
            return $this->jsonResponse([
                'message' => trans('app.id_of_instance_not_exists')
            ]);
        }

        $instance = Instance::find($id__Instance);

        if (!$instance) {
            return $this->jsonResponse([
                'message' => trans('app.item_non_exists')
            ]);
        }

        $this->ownerAccess($instance);

        $id = $id ?? $this->request->get('id');

        $validation = $this->validateData();

        if ($validation['success']) {
            $validation['message'] = '<i class="ui green check icon"></i>' . trans('app.saved');
            if ($this->request->get('do_redirect')) {
                $validation['redirect'] = session()->pull('url.intended', '/operations');
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
                'id__Instance',
                'operated_at',
                'note',
                'condition',
                'price',
                'currency'
            ]));

            $operation->update($operation_input);

            // Save operation Photos
            $this->syncPhotos($operation, $this->request->get('operation_photos'));
        }

        return $this->jsonResponse($validation);
    }

    private function validateData(): array
    {
        $data = [];

        $v = $this->getValidationFactory()->make($this->request->all(), [
            'id__OperationType' => 'required|numeric|min:1',
            'operated_at' => 'required',
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
        $operation->photos()->sync($photos ?? []);
    }

    private function getOperationTypesForDropdown(): array
    {
        return ['0' => '---'] + OperationType::ofCurrentUser()->pluck('title', 'id')->all();
    }

    private function getCurrenciesForDropDown(): array
    {
        return [
            'UAH' => trans('app.uah'),
            'USD' => trans('app.usd'),
            'EUR' => trans('app.eur')
        ];
    }

    private function getConditionsForDropDown(): array
    {
        return [
            'NONE' => '---',
            'NEW' => trans('app.new'),
            'USED' => trans('app.used')
        ];
    }
}
