<?php

namespace App\Services;

use Validator;
use App\Models\Item;
use App\Enums\ItemStatus;
use App\Models\Perumahan;
use App\Models\Announcement;
use App\Traits\ReturnResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;


class ItemApiService
{

    public function getItem(): Builder
    {
        return Item::query();
    }

    public function getMyItem($id): Builder
    {
        return Item::query()->where('submit_id', $id);
    }

    public function getTakenItem(): Collection
    {
        return $this->getItem()->where('status', ItemStatus::TAKEN->value)->get();
    }

    public function getNotTakenItem(): Collection
    {
        return $this->getItem()->where('status', ItemStatus::NOTTAKEN->value)->get();
    }

    public function getVerifiedItem($type): Collection
    {
        return $this->getItem()->where('type', $type)->where('status', ItemStatus::NOTTAKEN->value)->where('verified', true)->get();
    }
    public function getNotVerifiedItem(): Collection
    {
        return $this->getItem()->where('verified', false)->get();
    }

    public function getMyPostedItem($id): Collection
    {
        return $this->getMyItem($id)->get();
    }

    public function validatePostItem($data)
    {
        $validate = Validator::make(
            $data,
            [
                'item' => 'required|max:25',
                'description' => 'required',
                'location' => 'required',
                'type' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ],
            [
                'item.required' => "Item Name can't be empty",
                'item.max' => "Item Name max length is 25",
                'description.required' => "Description can't be empty",
                'location.required' => "Location can't be empty",
                'type.required' => "Type can't be empty",
                'image.required' => "Image can't be empty",
                'image.image' => "Image must be an image",
                'image.mimes' => "Image format not supported",
                'image.max' => 'Image maximum size is 2048KB'
            ]
        );
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        return true;
    }

    public function getItemImages($type, $name)
    {
        // Assuming the image is stored in the 'public/images/{type}' directory
        $imagePath = public_path('images/' . $type . '/' . $name);

        if (file_exists($imagePath)) {
            // Generate the URL to the image
            $imageUrl = asset('images/' . $type . '/' . $name);

            return response()->json([
                'image_url' => $imageUrl
            ]);
        } else {
            return response()->json([
                'message' => 'Image not found'
            ], 404);
        }
    }


    public function saveImage($image, $imageName, $type)
    {
        // $image is an image from the request, just like $request->image
        try {
            if ($type == 'lost') {
                $save = $image->move(public_path('images/lost/'), $imageName);
            } else if ($type == 'found') {
                $save = $image->move(public_path('images/found/'), $imageName);
            }
            return true;
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Image save failed'
            ], 500);
        }

    }

    public function storeItem($data)
    {
        DB::beginTransaction();
        try {
            Item::create($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => 'Data insertion failed'
            ], 500);
        }
    }

    public function changeStatus($id, $status)
    {
        $item = $this->getItem()->where('id', $id)->first();
        DB::beginTransaction();
        try {
            $item->status = $status;
            $item->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception;
        }
    }

    public function verifyItem($id)
    {
        $item = $this->getItem()->find($id);
        DB::beginTransaction();
        try {
            $item->verified = 1;
            $item->save();
            DB::commit();
            return 'success';
        } catch (\Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }
    public function cancelItem($id)
    {
        $item = $this->getItem()->find($id);
        DB::beginTransaction();
        try {
            $item->verified = 2;
            $item->save();
            DB::commit();
            return 'success';
        } catch (\Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }

}