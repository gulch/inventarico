<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $data = [
            'categories' => Category::paginate(24)
        ];

        return view('categories.index', $data);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->ownerAccess($category);
        $data = [
            'category' => $category
        ];

        return view('categories.edit', $data);
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
        $category = Category::find($id);
        $this->ownerAccess($category);

        if (is_null($category)) {
            return json_encode(['message' => trans('app.item_not_found')]);
        } else {
            $category->delete();
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
            $validation['message'] = '<i class="ui green check icon"></i>'.trans('app.saved');
            if ($this->request->get('do_redirect')) {
                $validation['redirect'] = Session::pull('url.intended', '/categories');
            }

            if ($id) {
                $category = Category::findOrFail($id);
                $this->ownerAccess($category);
            } else {
                $category = new Category;
                $category->setUserId();
                $category->save();
            }
            $category->update($this->request->all());
            $validation['id'] = $category->id;
        }

        return $this->jsonResponse($validation);
    }

    private function validateData()
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
