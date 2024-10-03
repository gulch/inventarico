<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationTypeRequest;
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

    public function store(StoreOperationTypeRequest $request): JsonResponse
    {
        return $this->saveItem($request);
    }

    public function update(StoreOperationTypeRequest $request, int $id): JsonResponse
    {
        return $this->saveItem($request, $id);
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

    private function saveItem(StoreOperationTypeRequest $request, ?int $id = null): JsonResponse
    {
        $id ??= $request->get('id');

        $result = [];

        $validated_input = $request->validated();

        $result['message'] = '✔️ ' . trans('app.saved');
        $result['success'] = 1;

        if ($request->get('do_redirect')) {
            $result['redirect'] = session()->pull('url.intended', '/operation-types');
        }

        if (! $id) {
            $operationType = new OperationType();
            $operationType->setUserId();
            $operationType->save();
        } else {
            $operationType = OperationType::findOrFail($id);
            $this->ownerAccess($operationType);
        }

        $operationType->update($$validated_input);

        $request['id'] = $operationType->id;

        return $this->jsonResponse($result);
    }
}
