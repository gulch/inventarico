<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\{Session, URL};
use App\Models\{Item, Category};

class ItemsController extends Controller
{
    public function index()
    {
        $data = [
            'items' => Item::ofCurrentUser()->latest()->paginate(24)
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
            'categories' => $this->getCategoriesForDropdown()
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
            'categories' => $this->getCategoriesForDropdown()
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

            $item_input = array_map('trim', $this->request->only('title', 'description', 'id__Photo', 'id__Category'));
            $overview = $this->getOverview();
            $item_input = array_merge($item_input, compact('overview'));

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
        $categories = ['0' => '---'] + Category::ofCurrentUser()->pluck('title', 'id')->all();

        return $categories;
    }

    private function getOverview()
    {
        $title = $this->request->get('o_title');
        $description = $this->request->get('o_description');
        $value = $this->request->get('o_value');

        $overview = [];
        if ($count = sizeof($title)) {
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
}
