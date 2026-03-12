<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue; // Wajib untuk background process

class UsersImport implements ToModel, WithBatchInserts, WithChunkReading, ShouldQueue
{
    public function model(array $row)
    {
        // Skip jika baris kosong (opsional)
        if (!isset($row[0])) return null;

        return new User([
            'name'     => $row[0],
            'nim_nip'  => $row[1],
            'email'    => $row[2],
            'role'     => $row[3] ?? 'mahasiswa',
            'password' => Hash::make($row[1]), // Password default pakai NIM/NIP
        ]);
    }

    // Memasukkan ke database tiap 10 data
    public function batchSize(): int
    {
        return 10;
    }

    // Membagi file Excel menjadi potongan 10 baris untuk antrean
    public function chunkSize(): int
    {
        return 10;
    }
}
