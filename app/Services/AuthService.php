<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\Item;
use App\Models\User;
use App\Enums\ItemStatus;
use App\Models\Perumahan;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;


class AuthService
{

    public function validateRegisterData($data)
    {
        $validate = Validator::make(
            $data,
            [
                'name' => 'required',
                'nim' => 'numeric|unique:users,nim|required',
                'email' => 'email|unique:users,email|required|regex:/^[\w\-\.]+@students\.polibatam\.ac\.id$/',
                'password' => 'min:8|required',
            ],
            [
                'name.required' => "Name field can't be empty",
                'nim.required' => "NIM field can't be empty",
                'nim.numeric' => 'NIM value not valid',
                'nim.unique' => 'This NIM has been used',
                'email.required' => "Email field can't be empty",
                'email.email' => "Email field must be filled with email",
                'email.unique' => 'This Email has been used',
                'email.regex' => 'Not a valid email',
                'password.required' => "Password field can't be empty",
                'password.min' => "Password minimum length is 8 characters"
            ]
        );

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        return true;
    }
    public function validateLoginData($data)
    {
        $validate = Validator::make(
            $data,
            [
                'email' => 'required|email|regex:/^[\w\-\.]+@students\.polibatam\.ac\.id$/',
                'password' => 'required|min:8'
            ],
            [
                'email.required' => "Email field can't be empty",
                'email.email' => "Email field must be filled with email",
                'email.regex' => 'Not a valid email',
                'password.required' => "Password field can't be empty",
                'password.min' => "Password minimum length is 8 characters"
            ]
        );
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        return true;
    }

    public function createUser($data)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'nim' => $data['nim']
            ]);

            $user->assignRole('user');
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error('Registration error: ' . $exception->getMessage());
            dd($exception->getMessage());
            return response()->json([
                'message' => 'Register failed !'
            ], 500);
        }
    }

    public function authenticate($data)
    {
        $user = User::where('email', $data['email'])->first();
        if ($user && Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Login Success',
                'user' => new UserResource($user),
            ], 200);
        }

        // Return failure response
        return response()->json([
            'message' => 'Unauthorized. Invalid credentials.'
        ], 401);
    }


}
