<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Item as ItemResources;
use App\Models;
use Illuminate\Http\Request;
use Response;

class ItemController extends Controller
{
    public function getItem(Request $request, Models\Item $item)
    {
        $item->load('category');

        return Response::send(
            new ItemResources\DetailedResource($item)
        );
    }
}
