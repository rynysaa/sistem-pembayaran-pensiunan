<?php

namespace Database\Seeders;

use App\Models\Pensiunan;
use App\Models\RekeningPensiunan;
use Illuminate\Database\Seeder;

class RekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nip'            => 'JM-001-1985',
                'nama_bank'      => 'Bank Mandiri',
                'nomor_rekening' => '1370012345678',
                'status'         => 'aktif',
            ],
            [
                'nip'            => 'JM-002-1987',
                'nama_bank'      => 'Bank BNI',
                'nomor_rekening' => '0234567891',
                'status'         => 'aktif',
            ],
            [
                'nip'            => 'JM-003-1990',
                'nama_bank'      => 'Bank BRI',
                'nomor_rekening' => '012301098765501',
                'status'         => 'aktif',
            ],
            [
                'nip'            => 'JM-004-1988',
                'nama_bank'      => 'Bank BCA',
                'nomor_rekening' => '8730123456',
                'status'         => 'nonaktif',
            ],
            [
                'nip'            => 'JM-005-1983',
                'nama_bank'      => 'Bank BTN',
                'nomor_rekening' => '001201500012345',
                'status'         => 'aktif',
            ],
            [
                'nip'            => 'JM-006-1992',
                'nama_bank'      => 'Bank Mandiri',
                'nomor_rekening' => '1370098765432',
                'status'         => 'aktif',
            ],
            [
                'nip'            => 'JM-007-1986',
                'nama_bank'      => 'Bank BRI',
                'nomor_rekening' => '012301011223502',
                'status'         => 'nonaktif',
            ],
            [
                'nip'            => 'JM-008-1989',
                'nama_bank'      => 'Bank BCA',
                'nomor_rekening' => '8730987654',
                'status'         => 'aktif',
            ],
        ];

        foreach ($data as $item) {
            $pensiunan = Pensiunan::where('nip', $item['nip'])->first();

            if ($pensiunan) {
                RekeningPensiunan::firstOrCreate(
                    [
                        'nomor_rekening' => $item['nomor_rekening'],
                    ],
                    [
                        'pensiunan_id' => $pensiunan->id,
                        'nama_bank'    => $item['nama_bank'],
                        'atas_nama'    => $pensiunan->nama,
                        'status'       => $item['status'],
                    ]
                );
            }
        }
    }
}

