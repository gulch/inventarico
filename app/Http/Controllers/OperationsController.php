<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationRequest;
use App\Models\Instance;
use App\Models\Operation;
use App\Models\OperationType;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

use function session;
use function trans;
use function url;
use function view;

final class OperationsController extends Controller
{
    private const PAGINATE_COUNT = 25;

    public function index(): View
    {
        $operations = Operation::query()
            ->ofCurrentUser()
            ->with([
                'type',
                'instance' => function ($query): void {
                    $query->with([
                        'thing' => function ($query): void {
                            $query->with('photo');
                        },
                    ]);
                },
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
            'operationTypes' => ['0' => '-- ' . trans('app.all') . ' --'] + OperationType::pluck('title', 'id')->all(),
        ];

        return view('operations.index', $data);
    }

    public function create(int $id__Instance): View
    {
        $instance = Instance::findOrFail($id__Instance);

        $this->ownerAccess($instance);

        session()->put('url.intended', url()->previous());

        $data = [
            'operation' => null,
            'instance' => $instance,
            'currencies' => $this->getCurrenciesForDropDown(),
            'conditions' => $this->getConditionsForDropDown(),
            'operationTypes' => $this->getOperationTypesForDropdown(),
        ];

        return view('operations.create', $data);
    }

    public function edit(int $id): View
    {
        $operation = Operation::findOrFail($id);

        $this->ownerAccess($operation);

        session()->put('url.intended', url()->previous());

        $data = [
            'operation' => $operation,
            'instance' => $operation->instance,
            'currencies' => $this->getCurrenciesForDropDown(),
            'conditions' => $this->getConditionsForDropDown(),
            'operationTypes' => $this->getOperationTypesForDropdown(),
        ];

        return view('operations.edit', $data);
    }

    public function store(StoreOperationRequest $request): JsonResponse
    {
        return $this->saveItem($request);
    }

    public function update(StoreOperationRequest $request, int $id): JsonResponse
    {
        return $this->saveItem($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        $operation = Operation::findOrFail($id);

        $this->ownerAccess($operation);

        // Unsync photos
        $operation->photos()->sync([]);

        // Delete operation
        $operation->delete();

        return $this->jsonResponse(['success' => 'OK']);
    }

    private function saveItem(StoreOperationRequest $request, ?int $id = null): JsonResponse
    {
        $id__Instance = $request->get('id__Instance');

        if (! $id__Instance) {
            return $this->jsonResponse([
                'message' => trans('app.id_of_instance_not_exists'),
            ]);
        }

        $instance = Instance::find($id__Instance);

        if (null === $instance) {
            return $this->jsonResponse([
                'message' => trans('app.item_non_exists'),
            ]);
        }

        $this->ownerAccess($instance);

        $id ??= $request->get('id');

        $result = [];

        $validated_input = $request->validated();

        $result['message'] = '✔️ ' . trans('app.saved');
        $result['success'] = 1;

        if ($request->get('do_redirect')) {
            $result['redirect'] = session()->pull('url.intended', '/instances');
        }

        if (! $id) {
            $operation = new Operation();
            $operation->setUserId();
            $operation->save();
        } else {
            $operation = Operation::findOrFail($id);
            $this->ownerAccess($operation);
        }

        $result['id'] = $operation->id;

        $operation->update($validated_input);

        // Save operation Photos
        $this->syncPhotos($operation, $this->request->get('operation_photos'));

        return $this->jsonResponse($result);
    }

    /**
     * @param null|array<int, int> $photos
     */
    private function syncPhotos(Operation $operation, ?array $photos): void
    {
        $operation->photos()->sync($photos ?? []);
    }

    /**
     * @return array<string, string>
     */
    private function getOperationTypesForDropdown(): array
    {
        return ['0' => '---'] + OperationType::ofCurrentUser()->pluck('title', 'id')->all();
    }

    /**
     * @return array<string, string>
     */
    private function getCurrenciesForDropDown(): array
    {
        return [
            'UAH' => trans('app.uah'),
            'USD' => trans('app.usd'),
            'EUR' => trans('app.eur'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getConditionsForDropDown(): array
    {
        return [
            'NONE' => '---',
            'NEW' => trans('app.new'),
            'USED' => trans('app.used'),
        ];
    }
}
