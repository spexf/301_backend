<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Services\ItemApiService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    //
    public function __construct(protected ItemApiService $itemApiService)
    {
    }

    protected function respondWithResource($status, $message, $data)
    {
        return new ItemResource($status, $message, $data);
    }


    public function getAllItems()
    {
        $data = $this->itemApiService->getItem()->get();
        return $this->respondWithResource(true, 'item_lists', $data);
    }

    public function getTakedItem()
    {
        $data = $this->itemApiService->getTakenItem();
        return $this->respondWithResource(true, 'taked_items', $data);
    }

    public function getNotTakedItem()
    {
        $data = $this->itemApiService->getNotTakenItem();
        return $this->respondWithResource(true, 'not_taked_items', $data);
    }

    public function storeItem(Request $request)
    {
        $validate = $this->itemApiService->validatePostItem($request->all());
        if ($validate !== true) {
            return $validate;
        }
        $imageNewName = time() . '.' . $request->image->extension();
        $this->itemApiService->saveImage($request->image, $imageNewName, $request->type);
        $this->itemApiService->storeItem([
            'submit_id' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'type' => $request->type,
            'image' => $imageNewName
        ]);


    }
}