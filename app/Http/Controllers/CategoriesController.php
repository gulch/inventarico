<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Session;

use function request;

final class CategoriesController extends Controller
{
    public static function getCategoriesForDropdown()
    {
        return Category::ofCurrentUser()
            ->orderBy('title')
            ->get()
            ->toTree();
    }
    public function index()
    {
        $data = [
            'categories' => Category::ofCurrentUser()->orderBy('title')->paginate(10),
        ];

        return view('categories.index', $data);
    }

    public function create()
    {
        $data = [
            'parent_category' => 0,
            'parent_categories' => self::getCategoriesForDropdown(),
        ];

        return view('categories.create', $data);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->ownerAccess($category);
        $data = [
            'category' => $category,
            'parent_category' => $category->parent_id,
            'parent_categories' => self::getCategoriesForDropdown(),
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

        if (null === $category) {
            return $this->jsonResponse(['message' => trans('app.item_not_found')]);
        }

        if (count($category->items)) {
            return $this->jsonResponse([
                'message' => trans('app.category_has_things_cant_delete'),
            ]);
        }

        $category->delete();

        return json_encode(['success' => 'OK']);
    }

    private function saveItem($id = null)
    {
        if ( ! $id) {
            $id = $this->request->get('id');
        }

        $validation = $this->validateData();

        if ($validation['success']) {
            $validation['message'] = '<i class="ui green check icon"></i>' . trans('app.saved');
            if ($this->request->get('do_redirect')) {
                $validation['redirect'] = Session::pull('url.intended', '/categories');
            }

            if ($id) {
                $category = Category::findOrFail($id);
                $this->ownerAccess($category);
            } else {
                $category = new Category();
                $category->setUserId();
                $category->save();
            }
            $category->update($this->request->all());

            if ($parent_id = request('parent_id')) {
                $parent_category = Category::find($parent_id);

                if ($parent_category) {
                    $category->moveTo(0, $parent_category);
                    //$parent_category->addChild($category);
                }
            }

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
