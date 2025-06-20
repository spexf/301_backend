<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RoleApiService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected RoleApiService $roleService;

    // Constructor untuk dependency injection
    public function __construct(RoleApiService $roleService)
    {
        $this->roleService = $roleService;
    }

    // Fungsi untuk mengambil semua role

    public function index()
    {

        $role = $this->roleService->getAllRoles();
        $admin = User::role('admin')->get(); // Memanggil service untuk mengambil semua role
        $user = User::role('user')->get(); // Memanggil service untuk mengambil semua role
        $banned = User::role('banned')->get(); // Memanggil service untuk mengambil semua role
        $role[0]['user_count'] = count($admin);
        $role[1]['user_count'] = count($user);
        $role[2]['user_count'] = count($banned);
        try {
            return response()->json($role, 200); // Mengembalikan response JSON dengan status 200
        } catch (\Exception $e) {
            return $e;
            // return response()->json(['message' => 'Error fetching roles'], 500);
        }
    }

    // Fungsi untuk mengambil role berdasarkan ID
    public function show($id)
    {
        try {
            $role = $this->roleService->getRoleById($id); // Memanggil service untuk mengambil role berdasarkan ID
            return response()->json($role, 200); // Mengembalikan response JSON dengan status 200
        } catch (\Exception $e) {
            return response()->json(['message' => 'Role not found'], 404);
        }
    }

    // Fungsi untuk membuat role baru
    public function store(Request $request)
    {
        try {
            // Validasi data yang diterima dari request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            // Memanggil service untuk menyimpan role baru
            $role = $this->roleService->createRole($validatedData);
            return response()->json($role, 201); // Mengembalikan response JSON dengan status 201
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating role'], 500);
        }
    }

    // Fungsi untuk memperbarui role berdasarkan ID
    public function update(Request $request, $id)
    {
        try {
            // Validasi data yang diterima dari request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            // Memanggil service untuk memperbarui role
            $role = $this->roleService->updateRole($id, $validatedData);
            return response()->json($role, 200); // Mengembalikan response JSON dengan status 200
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating role'], 500);
        }
    }

    // Fungsi untuk menghapus role berdasarkan ID
    public function destroy($id)
    {
        try {
            // Memanggil service untuk menghapus role
            $this->roleService->deleteRole($id);
            return response()->json(['message' => 'Role deleted successfully'], 200); // Mengembalikan response sukses
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting role'], 500);
        }
    }
}
