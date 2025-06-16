<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthService;
use Auth;
use Hash;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }
    public function register(Request $request)
    {
        $validate = $this->authService->validateRegisterData($request->all());
        if ($validate !== true) {
            return $validate;
        }
        return $this->authService->createUser($request->all());
    }

    public function login(Request $request)
    {
        $validate = $this->authService->validateLoginData($request->all());
        if ($validate !== true) {
            return $validate;
        }
        return $this->authService->authenticate($request->all());

    }

    public function authValidate()
    {
        return auth()->user()->roles[0];
    }

    public function redirectLogin()
    {
        $user = auth()->user();
        Auth::loginUsingId($user->id);
        return redirect()->to('/admin');
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token) {
            $token->delete();
        });
        return response()->json([
            'message' => 'Logout Success',
        ], 200);
    }

}