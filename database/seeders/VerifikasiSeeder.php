<?php

namespace Database\Seeders;

use App\Models\Pensiunan;
use App\Models\User;
use App\Models\VerifikasiData;
use Illuminate\Database\Seeder;

class VerifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hrdUser = User::where('role', 'hrd')->first();
        $hrdId   = $hrdUser?->id;

        $data = [
            [
                'nip'                => 'JM-001-1985',
                'status'             => 'diterima',
                'tanggal_verifikasi' => now()->subDays(5),
                'diverifikasi_oleh'  => $hrdId,
                'catatan'            => 'Data identitas dan SK Pensiun terverifikasi lengkap serta sah.',
            ],
            [
                'nip'                => 'JM-002-1987',
                'status'             => 'diterima',
                'tanggal_verifikasi' => now()->subDays(3),
                'diverifikasi_oleh'  => $hrdId,
                'catatan'            => 'Dokumen kepegawaian dan rekening bank telah diverifikasi valid.',
            ],
            [
                'nip'                => 'JM-003-1990',
                'status'             => 'menunggu',
                'tanggal_verifikasi' => null,
                'diverifikasi_oleh'  => null,
                'catatan'            => null,
            ],
            [
                'nip'                => 'JM-004-1988',
                'status'             => 'ditolak',
                'tanggal_verifikasi' => now()->subDays(1),
                'diverifikasi_oleh'  => $hrdId,
                'catatan'            => 'Terdapat ketidaksesuaian data NIK pada berkas KTP fisik. Mohon perbarui data dokumen pendukung.',
            ],
            [
                'nip'                => 'JM-005-1983',
                'status'             => 'diterima',
                'tanggal_verifikasi' => now()->subDays(7),
                'diverifikasi_oleh'  => $hrdId,
                'catatan'            => 'Data pensiun pejabat terverifikasi dan disetujui sesuai SK Direksi.',
            ],
            [
                'nip'                => 'JM-006-1992',
                'status'             => 'menunggu',
                'tanggal_verifikasi' => null,
                'diverifikasi_oleh'  => null,
                'catatan'            => null,
            ],
            [
                'nip'                => 'JM-007-1986',
                'status'             => 'menunggu',
                'tanggal_verifikasi' => null,
                'diverifikasi_oleh'  => null,
                'catatan'            => null,
            ],
            [
                'nip'                => 'JM-008-1989',
                'status'             => 'menunggu',
                'tanggal_verifikasi' => null,
                'diverifikasi_oleh'  => null,
                'catatan'            => null,
            ],
        ];

        foreach ($data as $item) {
            $pensiunan = Pensiunan::where('nip', $item['nip'])->first();

            if ($pensiunan) {
                VerifikasiData::firstOrCreate(
                    [
                        'pensiunan_id' => $pensiunan->id,
                    ],
                    [
                        'status'             => $item['status'],
                        'tanggal_verifikasi' => $item['tanggal_verifikasi'],
                        'diverifikasi_oleh'  => $item['diverifikasi_oleh'],
                        'catatan'            => $item['catatan'],
                    ]
                );
            }
        }
    }
}

