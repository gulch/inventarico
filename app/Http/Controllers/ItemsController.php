<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use gulch\Transliterato\BatchProcessor;
use gulch\Transliterato\Scheme\EngToRusKeyboardLayout;
use gulch\Transliterato\Scheme\EngToUkrKeyboardLayout;
use gulch\Transliterato\Scheme\RusToEngKeyboardLayout;
use gulch\Transliterato\Scheme\RusToUkrKeyboardLayout;
use gulch\Transliterato\Scheme\UkrToEngKeyboardLayout;
use gulch\Transliterato\Scheme\UkrToRusKeyboardLayout;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class ItemsController extends Controller
{
    private const PAGINATE_COUNT = 25;

    public function index()
    {
        $items = Item::with('photo', 'category', 'operations')
            ->ofCurrentUser();

        $items = $this->applyCategory($items);

        $items = $this->applySort($items);

        $items = $this->applyAvailability($items);

        $items = $this->applySearch($items);

        $items = $items->paginate(self::PAGINATE_COUNT);

        $data = [
            'items' => $items,
            'categories' => $this->getCategoriesForDropdown(),
            'selected_category' => $this->request->input('category') ?? 0,
        ];

        return view('items.index', $data);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        $this->ownerAccess($item);
        $data = [
            'item' => $item
        ];

        return view('items.show', $data);
    }

    public function create()
    {
        $data = [
            'categories' => $this->getCategoriesForDropdown(),
            'selected_category' => 0,
        ];
        Session::put('url.intended', url(URL::previous()));

        return view('items.create', $data);
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $this->ownerAccess($item);
        Session::put('url.intended', url(URL::previous()));
        $data = [
            'item' => $item,
            'categories' => $this->getCategoriesForDropdown(),
            'selected_category' => $item->id__Category,
        ];

        return view('items.edit', $data);
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
        $item = Item::find($id);
        $this->ownerAccess($item);

        if (is_null($item)) {
            return json_encode(['message' => trans('app.item_not_found')]);
        } else {
            // Unsync photos from operations
            if ($item->operations) {
                foreach ($item->operations as $o) {
                    $o->photos()->sync([]);
                }
                // Delete operations
                $item->operations()->delete();
            }

            // Delete item
            $item->delete();
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
            $validation['message'] = '<i class="ui green check icon"></i>' . trans('app.saved');
            if ($this->request->get('do_redirect')) {
                $validation['redirect'] = Session::pull('url.intended', '/items');
            }

            if ($id) {
                $item = Item::findOrFail($id);
                $this->ownerAccess($item);
            } else {
                $item = new Item;
                $item->setUserId();
                $item->save();
            }
            $validation['id'] = $item->id;

            $item_input = \array_map(
                'trim',
                $this->request->only([
                    'title',
                    'description',
                    'is_archived',
                    'id__Photo',
                    'id__Category'
                ])
            );
            $overview = $this->getOverview();
            $item_input = \array_merge($item_input, compact('overview'));

            $item_input = $this->setCheckboxesValues($item_input);

            $item->update($item_input);
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

    private function applyCategory($items)
    {
        $category_id = $this->request->input('category');

        if (!$category_id) {
            return $items;
        }

        $category = Category::find($category_id);

        if (!$category) {
            return $items;
        }

        $items->whereIn('id__Category',
            \array_merge([$category_id], $category->getDescendants()->pluck('id')->toArray()));

        return $items;
    }

    private function applySort($items)
    {
        $sort = $this->request->input('sort');

        switch ($sort) {
            case 'updated_desc':
                $items->orderBy('updated_at', 'desc');
                break;
            case 'updated_asc':
                $items->orderBy('updated_at');
                break;
            case 'alphabet_desc':
                $items->orderBy('title', 'desc');
                break;
            case 'alphabet_asc':
                $items->orderBy('title', 'asc');
                break;
            case 'created_asc':
                $items->orderBy('created_at');
                break;
            case 'created_desc':
            default:
                $items->orderBy('created_at', 'desc');
        }

        return $items;
    }

    private function applyAvailability($items)
    {
        $availability = $this->request->input('availability');

        switch ($availability) {
            case 'available':
                $items->available();
                break;
            case 'archived':
                $items->archived();
                break;
        }

        return $items;
    }

    private function applySearch($items)
    {
        $q = $this->request->input('q');

        if ($q) {
            $items->where(function($query) use ($q) {
                $query->where('title', 'like', '%' . $q . '%');
                // transliterato
                $results = self::transliterato($q);
                foreach ($results as $result) {
                    $query->orWhere('title', 'like', '%' . $result . '%');
                }
            });
        }

        return $items;
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
