<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategorytRequest;
use App\Models\Category;
use Illuminate\Support\Collection;

use function session;
use function trans;
use function view;

final class CategoriesController extends Controller
{
    public static function getCategoriesForDropdown(): Collection
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

    public function edit(Category $category)
    {
        $this->ownerAccess($category);

        $data = [
            'category' => $category,
            'parent_category' => $category->parent_id,
            'parent_categories' => self::getCategoriesForDropdown(),
        ];

        return view('categories.edit', $data);
    }

    public function store(StoreCategorytRequest $request)
    {
        return $this->saveCategory($request);
    }

    public function update(StoreCategorytRequest $request, Category $category)
    {
        return $this->saveCategory($request, $category);
    }

    public function destroy(Category $category)
    {
        $this->ownerAccess($category);

        /* Check if category has things */
        if ($category->things->count() > 0) {
            return $this->jsonResponse([
                'message' => trans('app.category_has_things_cant_delete'),
            ]);
        }

        /* Check if category is parent and has children */
        if ($category->isParent()) {
            return $this->jsonResponse([
                'message' => trans('app.category_is_parent_cant_delete'),
            ]);
        }

        $category->delete();

        return $this->jsonResponse(['success' => 'OK']);
    }

    private function saveCategory(StoreCategorytRequest $request, ?Category $category = null)
    {
        if ($category) {
            $this->ownerAccess($category);
        } else {
            $category = new Category();
            $category->setUserId();
            $category->save();
        }

        $category->update($request->validated());

        $result = [
            'id' => $category->id,
            'success' => 1,
            'message' => '✔️ ' . trans('app.saved'),
        ];

        if ($request->get('do_redirect')) {
            $result['redirect'] = session()->pull('url.intended', '/categories');
        }

        if ($parent_id = $request->get('parent_id')) {
            $parent_category = Category::find($parent_id);

            if ($parent_category) {
                $category->moveTo(0, $parent_category);
            }
        }

        return $this->jsonResponse($result);
    }
}
