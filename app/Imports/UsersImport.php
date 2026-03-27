<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use Throwable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithEvents,
    SkipsEmptyRows,
    SkipsOnError
};
use Maatwebsite\Excel\Events\AfterImport;

class UsersImport implements
    ToModel,
    WithHeadingRow,
    ShouldQueue,
    WithChunkReading,
    WithEvents,
    SkipsEmptyRows,
    SkipsOnError
{
    protected int $userId;
    protected string $cacheKey;

    public function __construct(int $userId, string $cacheKey)
    {
        $this->userId = $userId;
        $this->cacheKey = $cacheKey;
    }

    public function model(array $row)
    {
        $this->updateProgress();

        $nama   = trim($row['nama_wajib'] ?? '');
        $nimNip = trim($row['nim_nip_wajib'] ?? '');

        if (!$nama || !$nimNip) return null;

        // Skip jika sudah ada
        if (User::where('nim_nip', $nimNip)->exists()) return null;

        $roleTxt = trim($row['role_default_mahasiswa'] ?? 'Mahasiswa');

        $role = Role::where('nama_role', 'LIKE', "%{$roleTxt}%")->first()
            ?? Role::where('kode_role', 'MHS')->first();

        if (!$role) return null;

        return new User([
            'name'      => $nama,
            'nim_nip'   => $nimNip,
            'email'     => $row['email_opsional'] ?? null,
            'role_id'   => $role->id,
            'is_active' => 1,
            'password'  => Hash::make($row['password_default_nim_nip'] ?? $nimNip),
        ]);
    }

    public function chunkSize(): int
    {
        return 10;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                $progress = Cache::get($this->cacheKey);

                if ($progress) {
                    $progress['current'] = $progress['total'];
                    $progress['status'] = 'completed';
                    $progress['message'] = 'Import selesai';

                    Cache::put($this->cacheKey, $progress, now()->addHours(2));
                }
            },
        ];
    }

    public function onError(Throwable $e): void
    {
        Cache::put($this->cacheKey, [
            'current' => 0,
            'total' => 0,
            'status' => 'failed',
            'message' => $e->getMessage(),
        ], now()->addHours(2));
    }

    protected function updateProgress(): void
    {
        $progress = Cache::get($this->cacheKey);

        if (!$progress) return;

        $progress['current'] = min(
            ($progress['current'] ?? 0) + 1,
            $progress['total'] ?? 0
        );

        $progress['message'] = 'Memproses data...';

        Cache::put($this->cacheKey, $progress, now()->addHours(2));
    }
}
