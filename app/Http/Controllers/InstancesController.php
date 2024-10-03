<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstanceRequest;
use App\Models\Instance;
use App\Models\Thing;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

use function array_merge;
use function count;
use function is_array;
use function json_encode;
use function session;
use function trim;
use function url;

final class InstancesController extends Controller
{
    public function create(int $id__Thing): View
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

    public function edit(int $id): View
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

    public function store(StoreInstanceRequest $request): JsonResponse
    {
        return $this->saveInstance($request);
    }

    public function update(int $id, StoreInstanceRequest $request): JsonResponse
    {
        return $this->saveInstance($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        $instance = Instance::findOrFail($id);

        $this->ownerAccess($instance);

        // Unsync photos from operations
        foreach ($instance->operations as $o) {
            $o->photos()->sync([]);
        }
        // Delete operations
        $instance->operations()->delete();

        // Delete item
        $instance->delete();

        return $this->jsonResponse(['success' => 'OK']);
    }

    private function saveInstance(StoreInstanceRequest $request, ?int $id = null): JsonResponse
    {
        $result = [];

        $validated_input = $request->validated();

        $result['message'] = '✔️ ' . trans('app.saved');
        $result['success'] = 1;

        if ($request->get('do_redirect')) {
            $result['redirect'] = session()->pull('url.intended', '/instances');
        }

        if (! $id) {
            $instance = new Instance();
            $instance->setUserId();
            $instance->save();
        } else {
            $instance = Instance::findOrFail($id);
            $this->ownerAccess($instance);
        }

        $result['id'] = $instance->id;

        $validated_input = array_merge($validated_input, ['overview' => $this->getOverview()]);

        $instance->update($validated_input);

        return $this->jsonResponse($result);
    }

    private function getOverview(): string
    {
        $title = $this->request->get('o_title');
        $description = $this->request->get('o_description');
        $value = $this->request->get('o_value');

        $overview = [];

        if (is_array($title)) {
            $count = count($title);
            for ($i = 0; $i < $count; $i++) {
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

        return (string) json_encode($overview, \JSON_UNESCAPED_UNICODE);
    }
}
