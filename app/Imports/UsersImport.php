<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Cari ID Role berdasarkan nama (Misal di Excel ditulis "Mahasiswa")
        // Jika tidak ditemukan, default ke Mahasiswa (MHS)
        $role = Role::where('nama_role', 'LIKE', '%' . $row['role'] . '%')->first();
        $roleId = $role ? $role->id : Role::where('kode_role', 'MHS')->first()->id;

        // 2. Cegah duplikasi NIM/NIP
        $existingUser = User::where('nim_nip', $row['nim_nip'])->first();
        if ($existingUser) {
            return null; 
        }

        // 3. Simpan data ke Database
        return new User([
            'name'      => $row['nama'],
            'nim_nip'   => $row['nim_nip'],
            'email'     => $row['email'] ?? null,
            'role_id'   => $roleId,
            'is_active' => 1,
            // Password default disamakan dengan NIM/NIP jika kolom password kosong
            'password'  => Hash::make($row['password'] ?? $row['nim_nip']),
        ]);
    }
}
