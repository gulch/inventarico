<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Franzose\ClosureTable\Extensions\Collection as ClosureTableCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

use function session;
use function trans;
use function view;

final class CategoriesController extends Controller
{
    public static function getCategoriesForDropdown(): ClosureTableCollection
    {
        $categories = Category::query()
            ->ofCurrentUser()
            ->orderBy('title')
            ->get();

        return (new ClosureTableCollection($categories))->toTree();
    }

    public function index(): View
    {
        $data = [
            'categories' => Category::query()->ofCurrentUser()->orderBy('title')->paginate(10),
        ];

        return view('categories.index', $data);
    }

    public function create(): View
    {
        $data = [
            'parent_category' => 0,
            'parent_categories' => self::getCategoriesForDropdown(),
        ];

        return view('categories.create', $data);
    }

    public function edit(Category $category): View
    {
        $this->ownerAccess($category);

        $data = [
            'category' => $category,
            'parent_category' => $category->parent_id,
            'parent_categories' => self::getCategoriesForDropdown(),
        ];

        return view('categories.edit', $data);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        return $this->saveCategory($request);
    }

    public function update(StoreCategoryRequest $request, Category $category): JsonResponse
    {
        return $this->saveCategory($request, $category);
    }

    public function destroy(Category $category): JsonResponse
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

    private function saveCategory(
        StoreCategoryRequest $request,
        ?Category $category = null
    ): JsonResponse {
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
