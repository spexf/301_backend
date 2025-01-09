<?php

namespace App\Services;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
class TransactionService
{
    public function storeTransaction($data)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::create($data);
            DB::commit();
            return $transaction;
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => 'Data insertion failed'
            ], 500);
        }
    }

    public function storeChatImage($image, $imageName)
    {
        // $image is an image from the request, just like $request->image
        try {
            $save = $image->move(public_path('images/chat/'), $imageName);
            return true;
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Image save failed'
            ], 500);
        }

    }
    public function storeTransactionImage($image, $imageName)
    {
        // $image is an image from the request, just like $request->image
        try {
            $save = $image->move(public_path('images/transaction/'), $imageName);
            return true;
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Image save failed'
            ], 500);
        }

    }

    public function getTransactionImages($name)
    {
        // Assuming the image is stored in the 'public/images/{type}' directory
        $imagePath = public_path('images/transaction/' . $name);

        if (file_exists($imagePath)) {
            // Generate the URL to the image
            $imageUrl = asset('images/transaction/' . $name);

            return response()->json([
                'image_url' => $imageUrl
            ]);
        } else {
            return response()->json([
                'message' => 'Image not found'
            ], 404);
        }
    }

    public function getChatImages($name)
    {
        // Assuming the image is stored in the 'public/images/{type}' directory
        $imagePath = public_path('images/chat/' . $name);

        if (file_exists($imagePath)) {
            // Generate the URL to the image
            $imageUrl = asset('images/chat/' . $name);

            return response()->json([
                'image_url' => $imageUrl
            ]);
        } else {
            return response()->json([
                'message' => 'Image not found'
            ], 404);
        }
    }

    public function getTransaction()
    {
        return Transaction::query();
    }
}