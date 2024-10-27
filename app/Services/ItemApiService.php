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

    public function getVerifiedItem(): Collection
    {
        return $this->getItem()->where('verified', true)->get();
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
                'description' => 'required',
                'location' => 'required',
                'type' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg'
            ],
            [
                'description.required' => "Description can't be empty",
                'location.required' => "Location can't be empty",
                'type.required' => "Type can't be empty",
                'image.required' => "Image can't be empty",
                'image.image' => "Image must be an image",
                'image.mimes' => "Image format not supported"
            ]
        );
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        return true;
    }

    public function saveImage($image, $imageName, $type)
    {
        // $image is an image from the request, just like $request->image
        try {
            if ($type == 'lost') {
                $save = $image->move(public_path('images/lost/'), $imageName);
            } else if ($type == 'found') {
                $save = $image->move(public_path('images/lost/'), $imageName);
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

}
