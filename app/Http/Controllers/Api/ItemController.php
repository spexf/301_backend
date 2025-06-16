<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Enums\ItemStatus;
use Illuminate\Http\Request;
use App\Services\ItemApiService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;

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

    public function getFinishedItem()
    {
        return Item::get();
    }

    public function getFrontEndData()
    {
        $data = $this->itemApiService->getItem()->with('submited_by')->with('taked_by')->get();
        return $data;
    }

    public function getImage($type, $name)
    {
        return $this->itemApiService->getItemImages($type, $name);
    }

    public function getItemDetails($id)
    {
        $data = $this->itemApiService->getItem()->where('id', $id)->with('submited_by')->with('taked_by')->first();
        return $this->respondWithResource(true, 'item_details', $data);
    }

    public function getAllItems()
    {
        $data = $this->itemApiService->getItem()->with('submited_by')->get();
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

    public function getAllVerifiedItem($type)
    {
        $data = $this->itemApiService->getVerifiedItem($type);
        return $this->respondWithResource(true, 'all_verified_item', $data);
    }

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

    public function getMyItems()
    {
        $data = $this->itemApiService->getItem()->where('submit_id', auth()->user()->id)->get();
        return $this->respondWithResource(true, 'my_uploads', $data);
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
            'item' => $request->item,
            'submit_id' => auth()->user()->id,
            'description' => $request->description,
            'location' => $request->location,
            'type' => $request->type,
            'image' => $imageNewName
        ]);


    }

    // END INSERT DATA
    // UPDATE DATA
    public function changeStatus($id, $status)
    {
        if ($status == ItemStatus::NOTTAKEN->value) {
            $this->itemApiService->changeStatus($id, ItemStatus::NOTTAKEN->value);
        } elseif ($status == ItemStatus::TAKEN->value) {
            $this->itemApiService->changeStatus($id, ItemStatus::TAKEN->value);
        } else {
            return response()->json([
                'status' => '422',
                'message' => 'Status invalid'
            ], 422);
        }
        return $this->respondWithResource(true, 'changed_item', $this->itemApiService->getItem()->where('id', $id)->first());

    }

    public function verifyItem($id)
    {
        $modify = $this->itemApiService->verifyItem($id);
        if ($modify == 'success') {
            return $this->respondWithResource(true, 'modify_data_success', 'Success');
        } else {
            return response()->json([
                'status' => false,
                'message' => 'modifying data failed'
            ], 500);
        }
    }
    public function cancelItem($id)
    {
        $modify = $this->itemApiService->cancelItem($id);
        if ($modify == 'success') {
            return $this->respondWithResource(true, 'modify_data_success', 'Success');
        } else {
            return response()->json([
                'status' => false,
                'message' => 'modifying data failed'
            ], 500);
        }
    }
    // END UPDATE DATA
    // DELETE DATA
    // END DELETE DATA
}