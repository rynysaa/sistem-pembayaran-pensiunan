<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use App\Models\Pensiunan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keuanganUser = User::where('role', 'keuangan')->first();
        $keuanganId   = $keuanganUser?->id;

        $pensiunanUser = User::where('role', 'pensiunan')->first();
        if ($pensiunanUser) {
            Pensiunan::where('nip', 'JM-001-1985')->update(['user_id' => $pensiunanUser->id]);
        }

        $data = [
            // Periode Oktober 2026
            [
                'nip'                => 'JM-001-1985',
                'periode'            => '2026-10',
                'jumlah'             => 4500000,
                'status'             => 'berhasil',
                'tanggal_pembayaran' => '2026-10-05',
                'processed_by'       => $keuanganId,
                'keterangan'         => 'Pembayaran manfaat pensiun periode Oktober 2026 telah ditransfer.',
            ],
            [
                'nip'                => 'JM-002-1987',
                'periode'            => '2026-10',
                'jumlah'             => 5250000,
                'status'             => 'berhasil',
                'tanggal_pembayaran' => '2026-10-05',
                'processed_by'       => $keuanganId,
                'keterangan'         => 'Pembayaran manfaat pensiun periode Oktober 2026 telah ditransfer.',
            ],
            [
                'nip'                => 'JM-005-1983',
                'periode'            => '2026-10',
                'jumlah'             => 8000000,
                'status'             => 'berhasil',
                'tanggal_pembayaran' => '2026-10-05',
                'processed_by'       => $keuanganId,
                'keterangan'         => 'Pembayaran pensiun direksi periode Oktober 2026 telah ditransfer.',
            ],

            // Periode November 2026
            [
                'nip'                => 'JM-001-1985',
                'periode'            => '2026-11',
                'jumlah'             => 4500000,
                'status'             => 'berhasil',
                'tanggal_pembayaran' => '2026-11-05',
                'processed_by'       => $keuanganId,
                'keterangan'         => 'Pembayaran manfaat pensiun periode November 2026 telah diselesaikan.',
            ],
            [
                'nip'                => 'JM-002-1987',
                'periode'            => '2026-11',
                'jumlah'             => 5250000,
                'status'             => 'diproses',
                'tanggal_pembayaran' => null,
                'processed_by'       => $keuanganId,
                'keterangan'         => 'Sedang diproses dalam antrean kliring bank batch 2.',
            ],
            [
                'nip'                => 'JM-005-1983',
                'periode'            => '2026-11',
                'jumlah'             => 8000000,
                'status'             => 'menunggu',
                'tanggal_pembayaran' => null,
                'processed_by'       => null,
                'keterangan'         => 'Instruksi pembayaran diterbitkan, menunggu eksekusi transfer.',
            ],
        ];

        foreach ($data as $item) {
            $pensiunan = Pensiunan::where('nip', $item['nip'])->first();

            if ($pensiunan) {
                Pembayaran::firstOrCreate(
                    [
                        'pensiunan_id' => $pensiunan->id,
                        'periode'      => $item['periode'],
                    ],
                    [
                        'jumlah'             => $item['jumlah'],
                        'status'             => $item['status'],
                        'tanggal_pembayaran' => $item['tanggal_pembayaran'],
                        'processed_by'       => $item['processed_by'],
                        'keterangan'         => $item['keterangan'],
                    ]
                );
            }
        }
    }
}

