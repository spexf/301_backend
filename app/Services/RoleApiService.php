<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleApiService
{

    public function getRole()
    {
        return Role::query();
    }
    // Fungsi untuk mengambil semua role
    public function getAllRoles()
    {
        return Role::all(); // Mengambil semua role
    }

    // Fungsi untuk mendapatkan role berdasarkan ID
    public function getRoleById($roleId)
    {
        return Role::findOrFail($roleId); // Mencari role berdasarkan ID, jika tidak ditemukan, akan throw ModelNotFoundException
    }

    // Fungsi untuk membuat role baru
    public function createRole($data)
    {
        return Role::create($data); // Membuat role baru dengan data yang diberikan
    }

    // Fungsi untuk memperbarui role
    public function updateRole($roleId, $data)
    {
        $role = $this->getRoleById($roleId); // Menemukan role berdasarkan ID
        $role->update($data); // Memperbarui data role
        return $role;
    }

    // Fungsi untuk menghapus role
    public function deleteRole($roleId)
    {
        $role = $this->getRoleById($roleId); // Menemukan role berdasarkan ID
        return $role->delete(); // Menghapus role
    }
}
