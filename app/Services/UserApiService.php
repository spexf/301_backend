<?php

namespace App\Services;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
class UserApiService
{
    public function getUser()
    {
        return User::query();
    }

    public function changeUserRole($id, $role)
    {
        $user = $this->getUser()->where('id', $id)->first();
        if (!Role::where('name', $role)->exists()) {
            Role::create(['name' => $role, 'guard_name' => 'api']);
        }
        $user->removeRole($user->roles[0]->name);
        $user->assignRole($role);
        return 'success';
    }

    public function deleteUser($id) {
        $user = $this->getUser()->where('id', $id)->first();
        if(isset($user)){
            DB::beginTransaction();
            try {
                $user->delete();
                DB::commit();
                return 'success';
            } catch (\Exception $exception){
                DB::rollBack();
                return 'fail';
            }
        } else {
            return response()->json([
                'status'=> 404,
                'message'=>'User not found!'
            ], 404);
        }

    }
}