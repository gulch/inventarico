<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\OperationType;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

use function session;
use function trans;
use function url;
use function view;

final class OperationTypesController extends Controller
{
    public function index(): View
    {
        $data = [
            'operationTypes' => OperationType::ofCurrentUser()->latest()->paginate(10),
        ];

        return view('operation-types.index', $data);
    }

    public function create(): View
    {
        session()->put('url.intended', url()->previous());

        return view('operation-types.create');
    }

    public function edit(int $id): View
    {
        $operationType = OperationType::findOrFail($id);

        $this->ownerAccess($operationType);

        session()->put('url.intended', url()->previous());

        $data = [
            'operationType' => $operationType,
        ];

        return view('operation-types.edit', $data);
    }

    public function store(): JsonResponse
    {
        return $this->saveItem();
    }

    public function update(int $id): JsonResponse
    {
        return $this->saveItem($id);
    }

    public function destroy(int $id): JsonResponse
    {
        $operationType = OperationType::find($id);

        $this->ownerAccess($operationType);

        if (null === $operationType) {
            return $this->jsonResponse(['message' => trans('app.item_not_found')]);
        }
        if (count($operationType->operations)) {
            return $this->jsonResponse([
                'message' => trans('app.operationtype_has_operations_cant_delete'),
            ]);
        }

        // Delete Operation Type
        $operationType->delete();

        return $this->jsonResponse(['success' => 'OK']);
    }

    private function saveItem(?int $id = null): JsonResponse
    {
        if ( ! $id) {
            $id = $this->request->get('id');
        }

        $validation = $this->validateData();

        if ($validation['success']) {
            $validation['message'] = '<i class="ui green check icon"></i>' . trans('app.saved');
            if ($this->request->get('do_redirect')) {
                $validation['redirect'] = session()->pull('url.intended', '/operation-types');
            }

            if ($id) {
                $operationType = OperationType::findOrFail($id);
                $this->ownerAccess($operationType);
            } else {
                $operationType = new OperationType();
                $operationType->setUserId();
                $operationType->save();
            }
            $operationType->update($this->request->all());
            $validation['id'] = $operationType->id;
        }

        return $this->jsonResponse($validation);
    }

    /**
     * @return array<string, int|string>
     */
    private function validateData(): array
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
