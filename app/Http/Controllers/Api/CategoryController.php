<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResources;
use App\Http\Resources\Item as ItemResources;
use App\Models;
use Illuminate\Http\Request;
use Pagination;
use Response;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        $categories = Models\Category::orderBy('name', 'asc')->with('items')->get();

        return Response::send(
            CategoryResources\DefaultResource::collection($categories)
        );
    }

    public function getCategoryItems(Request $request, Models\Category $category)
    {
        $items = $category->items()->orderBy('name', 'asc')->orderBy('id', 'asc')->paginate($request->per_page ?? 9);

        return Response::send(
            new Pagination($items, ItemResources\DefaultResource::class),
        );
    }

    public function getAllCategoryItems(Request $request, Models\Category $category)
    {
        $items = $category->items()->orderBy('name', 'asc')->orderBy('id', 'asc')->get();

        return Response::send(
            ItemResources\DefaultResource::collection($items),
        );
    }
}
