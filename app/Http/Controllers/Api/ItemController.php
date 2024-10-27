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

    // GETTING DATA

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

    public function getAllVerifiedItem()
    {
        $data = $this->itemApiService->getVerifiedItem();
        return $this->respondWithResource(true, 'all_verified_item', $data);
    }

    //    ->where('type',$filter['type'])->where('status',$filter['status'])
    public function getMyFilteredItem($filterType = 'all', $filterStatus = 'all', $filterTime = 'latest')
    {
        $sortOrder = $filterTime == 'latest' ? 'desc' : 'asc';
        $query = $this->itemApiService->getMyItem(auth()->user()->id)->orderBy('created_at', $sortOrder);
        if ($filterType != 'all') {
            $query->where('type', $filterType);
        }
        if ($filterStatus != 'all') {
            $query->where('status', str_replace('_', ' ', $filterStatus));
        }
        $data = $query->get();
        return $this->respondWithResource(true, 'filtered_item', $data);
    }

    // END GETTING DATA
    // INSERT DATA

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
            'description' => $request->description,
            'location' => $request->location,
            'type' => $request->type,
            'image' => $imageNewName
        ]);


    }

    // END INSERT DATA
    // DELETE DATA
    // END DELETE DATA
}