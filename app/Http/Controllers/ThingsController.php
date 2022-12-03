<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thing;
use gulch\Transliterato\BatchProcessor;
use gulch\Transliterato\Scheme\EngToRusKeyboardLayout;
use gulch\Transliterato\Scheme\EngToUkrKeyboardLayout;
use gulch\Transliterato\Scheme\RusToEngKeyboardLayout;
use gulch\Transliterato\Scheme\RusToUkrKeyboardLayout;
use gulch\Transliterato\Scheme\UkrToEngKeyboardLayout;
use gulch\Transliterato\Scheme\UkrToRusKeyboardLayout;

class ThingsController extends Controller
{
    private const PAGINATE_COUNT = 25;

    public function index()
    {
        $things = Thing::query()
            ->with('photo', 'category', 'instances')
            ->ofCurrentUser();

        $things = $this->applyCategory($things);

        $things = $this->applySort($things);

        $things = $this->applyAvailability($things);

        $things = $this->applySearch($things);

        $things = $things->paginate(self::PAGINATE_COUNT);

        $data = [
            'things' => $things,
            'categories' => $this->getCategoriesForDropdown(),
            'selected_category' => $this->request->input('category') ?? 0,
        ];

        return view('things.index', $data);
    }

    public function show($id)
    {
        $thing = Thing::findOrFail($id);

        $this->ownerAccess($thing);

        $thing->instances = $thing->instances()
            ->with([
                'operations' => function($query) {
                    $query->orderBy('operated_at', 'desc');
                }
            ])
            ->orderBy('is_archived', 'asc')
            ->orderBy('published_at', 'desc')
            ->get();

        $data = [
            'thing' => $thing,
        ];

        return view('things.show.show', $data);
    }

    public function create()
    {
        $data = [
            'thing' => null,
            'categories' => $this->getCategoriesForDropdown(),
            'selected_category' => 0,
        ];

        session()->put('url.intended', url()->previous());

        return view('things.create', $data);
    }

    public function edit(int $id)
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

    public function store()
    {
        return $this->saveThing();
    }

    public function update(int $id)
    {
        return $this->saveThing($id);
    }

    public function destroy(int $id)
    {
        $thing = Thing::find($id);

        $this->ownerAccess($thing);

        if (is_null($thing)) {
            return json_encode(['message' => trans('app.item_not_found')]);
        }

        // remove instances
        if ($thing->instances) {

            // TODO: Unsync photos from instance operations

            $thing->instances()->delete();
        }

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

        return json_encode(['success' => 'OK']);
    }

    private function saveThing(?int $id = null)
    {
        if (!$id) {
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
                $thing = new Thing;
                $thing->setUserId();
                $thing->save();
            }

            $validation['id'] = $thing->id;

            $thing_input = \array_map(
                'trim',
                $this->request->only([
                    'title',
                    'description',
                    'is_archived',
                    'id__Photo',
                    'id__Category',
                    'published_at',
                ])
            );
            $overview = $this->getOverview();
            $thing_input = \array_merge($thing_input, compact('overview'));

            $thing_input = $this->setCheckboxesValues($thing_input);

            $thing->update($thing_input);
        }

        return $this->jsonResponse($validation);
    }

    private function validateData()
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

    private function getCategoriesForDropdown()
    {
        return CategoriesController::getCategoriesForDropdown();
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

    private function applyCategory($things)
    {
        $category_id = $this->request->input('category');

        if (!$category_id) {
            return $things;
        }

        $category = Category::find($category_id);

        if (!$category) {
            return $things;
        }

        $things->whereIn(
            'id__Category',
            \array_merge([$category_id], $category->getDescendants()->pluck('id')->toArray())
        );

        return $things;
    }

    private function applySort($things)
    {
        $sort = $this->request->input('sort');

        switch ($sort) {
            case 'published_desc':
                $things->orderBy('published_at', 'desc');
                break;
            case 'published_asc':
                $things->orderBy('published_at');
                break;
            case 'updated_desc':
                $things->orderBy('updated_at', 'desc');
                break;
            case 'updated_asc':
                $things->orderBy('updated_at');
                break;
            case 'alphabet_desc':
                $things->orderBy('title', 'desc');
                break;
            case 'alphabet_asc':
                $things->orderBy('title', 'asc');
                break;
            case 'created_asc':
                $things->orderBy('created_at');
                break;
            case 'created_desc':
            default:
                $things->orderBy('published_at', 'desc');
        }

        return $things;
    }

    private function applyAvailability($things)
    {
        $availability = $this->request->input('availability');

        switch ($availability) {
            case 'available':
                $things->available();
                break;
            case 'archived':
                $things->archived();
                break;
        }

        return $things;
    }

    private function applySearch($things)
    {
        $q = $this->request->input('q');

        if ($q) {
            $things->where(function ($query) use ($q) {
                $query->where('title', 'like', '%' . $q . '%');
                // transliterato
                $results = self::transliterato($q);
                foreach ($results as $result) {
                    $query->orWhere('title', 'like', '%' . $result . '%');
                }
            });
        }

        return $things;
    }

    public static function transliterato(string $q): array
    {
        $processor = new BatchProcessor(
            new EngToRusKeyboardLayout(),
            new EngToUkrKeyboardLayout(),
            new RusToEngKeyboardLayout(),
            new RusToUkrKeyboardLayout(),
            new UkrToEngKeyboardLayout(),
            new UkrToRusKeyboardLayout()
        );

        return $processor->process($q);
    }

    private function setCheckboxesValues($input)
    {
        if (!isset($input['is_archived'])) {
            $input['is_archived'] = 0;
        }

        return $input;
    }
}
