<?php

namespace App\Http\Controllers\Api;

use App\Enums\ItemStatus;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Services\TransactionService;
use App\Services\UserApiService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $transactionService, protected UserApiService $userApiService)
    {

    }

    public function storeTransactionItem(Request $request)
    {

        $chatImageNewName = time() . '.' . $request->chatImage->extension();
        $transactionImageNewName = time() . '.' . $request->transactionImage->extension();
        $this->transactionService->storeChatImage($request->chatImage, $chatImageNewName);
        $this->transactionService->storeTransactionImage($request->transactionImage, $transactionImageNewName);
        $item = Item::where('id', $request->item_id)->first();
        $user = $this->userApiService->getUser()->where('email', $request->email)->first();
        $item->take_id = $user->id;
        $item->status = ItemStatus::TAKEN->value;
        $item->save();
        $this->transactionService->storeTransaction([
            'item_id' => $request->item_id,
            'transaction_image' => $transactionImageNewName,
            'chat_image' => $chatImageNewName,
            'transaction_location' => $request->transaction_location
        ]);
    }

    public function getChatImages($image)
    {
        return $this->transactionService->getChatImages($image);
    }

    public function getTransactionImage($image)
    {
        return $this->transactionService->getTransactionImages($image);
    }

    public function getTransactions()
    {
        return $this->transactionService->getTransaction()->with('item')->get();
    }

    public function getTransaction($id)
    {
        return $this->transactionService->getTransaction()->with('item')->with('item.submited_by')->with('item.taked_by')->where('id', $id)->first();
    }


}
