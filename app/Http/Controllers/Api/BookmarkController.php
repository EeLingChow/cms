<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Resources\Shop as ShopResource;
use App\Models\Shop;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $shops = $request->user()->bookmarkedShops()->with(['categories', 'floor'])->get();

        return ShopResource::collection($shops);
    }

    public function store(Request $request, Shop $shop)
    {
        $request->user()->bookmarkedShops()->syncWithoutDetaching([$shop->id]);

        return response()->json(['message' => 'Bookmark added']);
    }

    public function destroy(Request $request, Shop $shop)
    {
        $request->user()->bookmarkedShops()->detach($shop->id);

        return response()->json(['message' => 'Bookmark removed']);
    }
}
