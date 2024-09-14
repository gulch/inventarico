<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
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

    public function store(): JsonResponse
    {
        return $this->saveThing();
    }

    public function update(int $id): JsonResponse
    {
        return $this->saveThing($id);
    }

    public function destroy(int $id): JsonResponse
    {
        $thing = Thing::find($id);

        $this->ownerAccess($thing);

        if (null === $thing) {
            return $this->jsonResponse(['message' => trans('app.item_not_found')]);
        }

        // remove instances

        // TODO: Unsync photos from instance operations

        $thing->instances()->delete();

        // TODO: Unsync photos from instance operations
        /* if ($thing->operations) {
            foreach ($thing->operations as $o) {
                $o->photos()->sync([]);
            }
            // Delete operations
            $thing->operations()->delete();
        } */

        // Delete item
        $thing->delete();

        return $this->jsonResponse(['success' => 'OK']);
    }

    private function saveThing(?int $id = null): JsonResponse
    {
        if (! $id) {
            $id = $this->request->get('id');
        }

        $validation = $this->validateData();

        if ($validation['success']) {
            $validation['message'] = '<i class="ui green check icon"></i>' . trans('app.saved');
            if ($this->request->get('do_redirect')) {
                $validation['redirect'] = session()->pull('url.intended', '/things');
            }

            if ($id) {
                $thing = Thing::findOrFail($id);
                $this->ownerAccess($thing);
            } else {
                $thing = new Thing();
                $thing->setUserId();
                $thing->save();
            }

            $validation['id'] = $thing->id;

            $thing_input = array_map(
                'trim',
                $this->request->only([
                    'title',
                    'description',
                    'is_archived',
                    'id__Photo',
                    'id__Category',
                    'published_at',
                ]),
            );
            $overview = $this->getOverview();
            $thing_input = array_merge($thing_input, compact('overview'));

            $thing_input = $this->setCheckboxesValues($thing_input);

            $thing->update($thing_input);
        }

        return $this->jsonResponse($validation);
    }

    /**
     * @return array<string, string>
     */
    private function validateData(): array
    {
        $data = [];

        $v = $this->getValidationFactory()->make($this->request->all(), [
            'title' => 'required',
            'id__Category' => 'required|numeric|min:1',
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

        return json_encode($overview, JSON_UNESCAPED_UNICODE);
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

    /**
     * @param array<string, string> $input
     * @return array<string, string>
     */
    private function setCheckboxesValues($input)
    {
        if (! isset($input['is_archived'])) {
            $input['is_archived'] = 0;
        }

        return $input;
    }
}
