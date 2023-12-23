<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResources;
use App\Http\Resources\Item as ItemResources;
use App\Models;
use Illuminate\Http\Request;
use Response;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        $categories = Models\Category::orderBy('id', 'asc')->get();
        $items = Models\Item::orderBy('id', 'asc')->get();

        $sitemap = [];

        foreach ($categories as $category) {
            $sitemap[] = [
                'type' => 'category',
                'data' => new CategoryResources\DefaultResource($category),
            ];
        }

        foreach ($items as $item) {
            $sitemap[] = [
                'type' => 'item',
                'data' => new ItemResources\BriefResource($item),
            ];
        }

        return Response::send($sitemap);
    }
}
