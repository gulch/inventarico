<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\{Item, Category};

class ItemsController extends Controller
{
    public function index()
    {
        $data = [
            'items' => Item::ofCurrentUser()->paginate(24)
        ];

        return view('items.index', $data);
    }

    public function create()
    {
        $data = [
            'categories' => $this->getCategoriesForDropdown()
        ];

        return view('items.create', $data);
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $this->ownerAccess($item);
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
                $validation['redirect'] = Session::pull('url.intended', '/categories');
            }

            if ($id) {
                $item = Item::findOrFail($id);
                $this->ownerAccess($item);
            } else {
                $item = new Item;
                $item->setUserId();
                $item->save();
            }
            $item->update($this->request->all());
            $validation['id'] = $item->id;
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
}
