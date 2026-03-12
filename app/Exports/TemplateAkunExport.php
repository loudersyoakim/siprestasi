<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemplateAkunExport implements WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'nama_wajib',
            'nim_nip_wajib',
            'email_opsional',
            'role_default_mahasiswa',
            'password_default_nim_nip'
        ];
    }
}
