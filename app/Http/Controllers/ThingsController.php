<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreThingRequest;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Thing;
use Franzose\ClosureTable\Extensions\Collection as ClosureTableCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use gulch\Transliterato\BatchProcessor;
use gulch\Transliterato\Scheme\EngToRusKeyboardLayout;
use gulch\Transliterato\Scheme\EngToUkrKeyboardLayout;
use gulch\Transliterato\Scheme\RusToEngKeyboardLayout;
use gulch\Transliterato\Scheme\RusToUkrKeyboardLayout;
use gulch\Transliterato\Scheme\UkrToEngKeyboardLayout;
use gulch\Transliterato\Scheme\UkrToRusKeyboardLayout;

use function array_map;
use function array_merge;
use function count;
use function is_array;

final class ThingsController extends Controller
{
    private const PAGINATE_COUNT = 25;

    /**
     * @return array<int, string>
     */
    public static function transliterato(string $q): array
    {
        $processor = new BatchProcessor(
            new EngToRusKeyboardLayout(),
            new EngToUkrKeyboardLayout(),
            new RusToEngKeyboardLayout(),
            new RusToUkrKeyboardLayout(),
            new UkrToEngKeyboardLayout(),
            new UkrToRusKeyboardLayout(),
        );

        return $processor->process($q);
    }

    public function index(): View
    {
        $query = Thing::query()
            ->with('photo', 'category', 'instances')
            ->ofCurrentUser();

        $query = $this->applyCategory($query);

        $query = $this->applySort($query);

        $query = $this->applyAvailability($query);

        $query = $this->applySearch($query);

        $data = [
            'things' => $query->paginate(self::PAGINATE_COUNT),
            'categories' => $this->getCategoriesForDropdown(),
            'selected_category' => $this->request->input('category') ?? 0,
        ];

        return view('things.index', $data);
    }

    public function show(int $id): View
    {
        $thing = Thing::findOrFail($id);

        $this->ownerAccess($thing);

        $thing->instances = $thing->instances()
            ->with([
                'operations' => function ($query): void {
                    $query->orderBy('operated_at', 'desc');
                },
            ])
            ->orderBy('is_archived', 'asc')
            ->orderBy('published_at', 'desc')
            ->get();

        $data = [
            'thing' => $thing,
        ];

        return view('things.show.show', $data);
    }

    public function create(): View
    {
        $data = [
            'thing' => null,
            'categories' => $this->getCategoriesForDropdown(),
            'selected_category' => 0,
        ];

        session()->put('url.intended', url()->previous());

        return view('things.create', $data);
    }

    public function edit(int $id): View
    {
        $thing = Thing::findOrFail($id);

        $this->ownerAccess($thing);

        session()->put('url.intended', url()->previous());

        $data = [
            'thing' => $thing,
            'categories' => $this->getCategoriesForDropdown(),
            'selected_category' => $thing->id__Category,
        ];

        return view('things.edit', $data);
    }

    public function store(StoreThingRequest $request): JsonResponse
    {
        return $this->saveThing($request);
    }

    public function update(int $id, StoreThingRequest $request): JsonResponse
    {
        return $this->saveThing($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        $thing = Thing::find($id);

        $this->ownerAccess($thing);

        if (null === $thing) {
            return $this->jsonResponse(['message' => trans('app.item_not_found')]);
        }

        // TODO: ??? Don't remove not empty thing

        foreach ($thing->instances as $instance) {

            foreach($instance->operations as $operation) {
                // unsync photos from instance operations
                $operation->photos()->sync([]);
            }

            // Delete operations of instance
            $instance->operations()->delete();
        }

        // remove instances
        $thing->instances()->delete();

        // Delete thing
        $thing->delete();

        return $this->jsonResponse(['success' => 'OK']);
    }

    private function saveThing(StoreThingRequest $request, ?int $id = null): JsonResponse
    {
        $id ??= $request->get('id');

        $result = [];

        $validated_input = $request->validated();

        $result['message'] = '✔️ ' . trans('app.saved');
        $result['success'] = 1;

        $result['message'] = '<i class="ui green check icon"></i>' . trans('app.saved');

        if ($request->get('do_redirect')) {
            $result['redirect'] = session()->pull('url.intended', '/things');
        }

        if (! $id) {
            $thing = new Thing();
            $thing->setUserId();
            $thing->save();
        } else {
            $thing = Thing::findOrFail($id);
            $this->ownerAccess($thing);
        }

        $result['id'] = $thing->id;

        $overview = $this->getOverview();

        $validated_input = array_merge($validated_input, compact('overview'));

        $thing->update($validated_input);

        return $this->jsonResponse($result);
    }

    private function getCategoriesForDropdown(): ClosureTableCollection
    {
        return CategoriesController::getCategoriesForDropdown();
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

        return (string) json_encode($overview, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Builder<Thing> $query
     * @return Builder<Thing>
     */
    private function applyCategory(Builder $query): Builder
    {
        $category_id = $this->request->input('category');

        if (! $category_id) {
            return $query;
        }

        $category = Category::find($category_id);

        if (! $category) {
            return $query;
        }

        $query->whereIn(
            'id__Category',
            array_merge([$category_id], $category->getDescendants()->pluck('id')->toArray()),
        );

        return $query;
    }

    /**
     * @param Builder<Thing> $query
     * @return Builder<Thing>
     */
    private function applySort(Builder $query): Builder
    {
        $sort = $this->request->input('sort');

        switch ($sort) {
            case 'published_desc':
                $query->orderBy('published_at', 'desc');
                break;
            case 'published_asc':
                $query->orderBy('published_at');
                break;
            case 'updated_desc':
                $query->orderBy('updated_at', 'desc');
                break;
            case 'updated_asc':
                $query->orderBy('updated_at');
                break;
            case 'alphabet_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'alphabet_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'created_asc':
                $query->orderBy('created_at');
                break;
            case 'created_desc':
            default:
                $query->orderBy('published_at', 'desc');
        }

        return $query;
    }

    /**
     * @param Builder<Thing> $query
     * @return Builder<Thing>
     */
    private function applyAvailability(Builder $query): Builder
    {
        $availability = $this->request->input('availability');

        switch ($availability) {
            case 'available':
                $query->available();
                break;
            case 'archived':
                $query->archived();
                break;
        }

        return $query;
    }

    /**
     * @param Builder<Thing> $query
     * @return Builder<Thing>
     */
    private function applySearch(Builder $query): Builder
    {
        $q = $this->request->input('q');

        if ($q) {
            $query->where(function ($query) use ($q): void {
                $query->where('title', 'like', '%' . $q . '%');
                // transliterato
                $results = self::transliterato($q);
                foreach ($results as $result) {
                    $query->orWhere('title', 'like', '%' . $result . '%');
                }
            });
        }

        return $query;
    }
}
