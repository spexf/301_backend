<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserApiService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserApiService $userApiService)
    {

    }

    public function getUser()
    {
        $data = $this->userApiService->getUser()->with('roles')->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        return response()->json([
            'status' => true,
            'message' => 'getting_user_success',
            'data' => $data
        ]);
    }

    public function banUser($id)
    {
        $this->userApiService->changeUserRole($id, 'banned');
        return response()->json([
            'status' => 200,
            'message' => 'user_banned',
        ]);
    }

    public function unbanUser($id)
    {
        $this->userApiService->changeUserRole($id, 'user');
        return response()->json([
            'status' => 200,
            'message' => 'user_unbanned',
        ]);
    }

    public function deleteUser($id)
    {
        return $this->userApiService->deleteUser($id);
    }
}
