<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PensiunanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nip'                 => 'JM-001-1985',
                'nik'                 => '3273010101850001',
                'nama'                => 'Budi Santoso',
                'jenis_kelamin'       => 'Laki-laki',
                'tanggal_lahir'       => '1960-05-10',
                'tanggal_mulai_kerja' => '1985-03-01',
                'tanggal_pensiun'     => '2020-05-10',
                'jabatan_terakhir'    => 'Kepala Divisi Operasional',
                'unit_kerja'          => 'Divisi Operasional Jalan Tol',
                'alamat'              => 'Jl. Merdeka No. 12, Jakarta Pusat',
                'no_telepon'          => '081234567890',
                'status'              => 'aktif',
            ],
            [
                'nip'                 => 'JM-002-1987',
                'nik'                 => '3273020202870002',
                'nama'                => 'Siti Rahayu',
                'jenis_kelamin'       => 'Perempuan',
                'tanggal_lahir'       => '1962-07-20',
                'tanggal_mulai_kerja' => '1987-09-15',
                'tanggal_pensiun'     => '2022-07-20',
                'jabatan_terakhir'    => 'Manajer Keuangan',
                'unit_kerja'          => 'Divisi Keuangan & Akuntansi',
                'alamat'              => 'Jl. Pahlawan No. 45, Bekasi',
                'no_telepon'          => '082345678901',
                'status'              => 'aktif',
            ],
            [
                'nip'                 => 'JM-003-1990',
                'nik'                 => '3273030303900003',
                'nama'                => 'Ahmad Fauzi',
                'jenis_kelamin'       => 'Laki-laki',
                'tanggal_lahir'       => '1965-11-30',
                'tanggal_mulai_kerja' => '1990-02-01',
                'tanggal_pensiun'     => '2025-11-30',
                'jabatan_terakhir'    => 'Supervisor Teknik',
                'unit_kerja'          => 'Divisi Teknik & Pemeliharaan',
                'alamat'              => 'Jl. Sudirman No. 78, Tangerang',
                'no_telepon'          => '083456789012',
                'status'              => 'aktif',
            ],
            [
                'nip'                 => 'JM-004-1988',
                'nik'                 => '3273040404880004',
                'nama'                => 'Dewi Kusumawati',
                'jenis_kelamin'       => 'Perempuan',
                'tanggal_lahir'       => '1963-03-15',
                'tanggal_mulai_kerja' => '1988-06-01',
                'tanggal_pensiun'     => '2023-03-15',
                'jabatan_terakhir'    => 'Kepala Bagian HRD',
                'unit_kerja'          => 'Divisi SDM & Organisasi',
                'alamat'              => 'Jl. Gatot Subroto No. 23, Jakarta Selatan',
                'no_telepon'          => '084567890123',
                'status'              => 'nonaktif',
            ],
            [
                'nip'                 => 'JM-005-1983',
                'nik'                 => '3273050505830005',
                'nama'                => 'Hendra Wijaya',
                'jenis_kelamin'       => 'Laki-laki',
                'tanggal_lahir'       => '1958-09-25',
                'tanggal_mulai_kerja' => '1983-11-01',
                'tanggal_pensiun'     => '2018-09-25',
                'jabatan_terakhir'    => 'Direktur Operasional',
                'unit_kerja'          => 'Direksi',
                'alamat'              => 'Jl. Kuningan No. 56, Jakarta Selatan',
                'no_telepon'          => '085678901234',
                'status'              => 'aktif',
            ],
            [
                'nip'                 => 'JM-006-1992',
                'nik'                 => '3273060606920006',
                'nama'                => 'Ratna Puspita',
                'jenis_kelamin'       => 'Perempuan',
                'tanggal_lahir'       => '1967-01-08',
                'tanggal_mulai_kerja' => '1992-04-01',
                'tanggal_pensiun'     => '2027-01-08',
                'jabatan_terakhir'    => 'Staf Senior Administrasi',
                'unit_kerja'          => 'Divisi Umum & Administrasi',
                'alamat'              => 'Jl. Pemuda No. 90, Depok',
                'no_telepon'          => '086789012345',
                'status'              => 'aktif',
            ],
            [
                'nip'                 => 'JM-007-1986',
                'nik'                 => '3273070707860007',
                'nama'                => 'Eko Prasetyo',
                'jenis_kelamin'       => 'Laki-laki',
                'tanggal_lahir'       => '1961-04-18',
                'tanggal_mulai_kerja' => '1986-07-01',
                'tanggal_pensiun'     => '2021-04-18',
                'jabatan_terakhir'    => 'Manajer IT',
                'unit_kerja'          => 'Divisi Teknologi Informasi',
                'alamat'              => 'Jl. Cempaka No. 34, Bogor',
                'no_telepon'          => '087890123456',
                'status'              => 'nonaktif',
            ],
            [
                'nip'                 => 'JM-008-1989',
                'nik'                 => '3273080808890008',
                'nama'                => 'Lestari Ningrum',
                'jenis_kelamin'       => 'Perempuan',
                'tanggal_lahir'       => '1964-06-22',
                'tanggal_mulai_kerja' => '1989-09-01',
                'tanggal_pensiun'     => '2024-06-22',
                'jabatan_terakhir'    => 'Kepala Bagian Keuangan',
                'unit_kerja'          => 'Divisi Keuangan & Akuntansi',
                'alamat'              => 'Jl. Anggrek No. 67, Ciputat',
                'no_telepon'          => '088901234567',
                'status'              => 'aktif',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\Pensiunan::firstOrCreate(
                ['nip' => $item['nip']],
                $item
            );
        }
    }
}
