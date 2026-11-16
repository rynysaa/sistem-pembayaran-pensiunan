<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hrdUser = User::where('email', 'hrd@jasamarga.co.id')->first();
        $keuanganUser = User::where('email', 'keuangan@jasamarga.co.id')->first();
        $pensiunanUser = User::where('email', 'pensiunan@jasamarga.co.id')->first();

        $notifications = [];

        if ($pensiunanUser) {
            $notifications[] = [
                'user_id'      => $pensiunanUser->id,
                'judul'        => 'Verifikasi Data Pensiunan Diterima',
                'pesan'        => 'Selamat! Data pensiunan Anda telah berhasil diverifikasi dan disetujui oleh Tim HRD PT Jasa Marga.',
                'jenis'        => 'verifikasi',
                'sudah_dibaca' => false,
                'dibaca_pada'  => null,
                'created_at'   => now()->subHours(2),
                'updated_at'   => now()->subHours(2),
            ];
            $notifications[] = [
                'user_id'      => $pensiunanUser->id,
                'judul'        => 'Pembayaran Manfaat Pensiun Berhasil',
                'pesan'        => 'Pembayaran manfaat pensiun periode Januari 2026 sebesar Rp 4.500.000 telah berhasil ditransfer ke rekening Bank Mandiri Anda.',
                'jenis'        => 'pembayaran',
                'sudah_dibaca' => false,
                'dibaca_pada'  => null,
                'created_at'   => now()->subHours(5),
                'updated_at'   => now()->subHours(5),
            ];
            $notifications[] = [
                'user_id'      => $pensiunanUser->id,
                'judul'        => 'Pembayaran Periode Februari 2026 Sedang Menunggu',
                'pesan'        => 'Jadwal pembayaran manfaat pensiun periode Februari 2026 telah diterbitkan dan menunggu proses verifikasi bagian keuangan.',
                'jenis'        => 'pembayaran',
                'sudah_dibaca' => true,
                'dibaca_pada'  => now()->subDays(2),
                'created_at'   => now()->subDays(3),
                'updated_at'   => now()->subDays(2),
            ];
            $notifications[] = [
                'user_id'      => $pensiunanUser->id,
                'judul'        => 'Pembaruan Kebijakan Manfaat Pensiun 2026',
                'pesan'        => 'Dihimbau kepada seluruh pensiunan PT Jasa Marga untuk melakukan pembaruan data rekening aktif sebelum tanggal 20 setiap bulannya.',
                'jenis'        => 'informasi',
                'sudah_dibaca' => true,
                'dibaca_pada'  => now()->subDays(4),
                'created_at'   => now()->subDays(5),
                'updated_at'   => now()->subDays(4),
            ];
        }

        if ($hrdUser) {
            $notifications[] = [
                'user_id'      => $hrdUser->id,
                'judul'        => 'Pengingat Verifikasi Berkas Pensiunan',
                'pesan'        => 'Terdapat 2 data pensiunan baru yang memerlukan peninjauan dan verifikasi kelengkapan berkas.',
                'jenis'        => 'verifikasi',
                'sudah_dibaca' => false,
                'dibaca_pada'  => null,
                'created_at'   => now()->subHours(3),
                'updated_at'   => now()->subHours(3),
            ];
            $notifications[] = [
                'user_id'      => $hrdUser->id,
                'judul'        => 'Laporan Rekapitulasi Data Pensiunan Q1',
                'pesan'        => 'Sistem telah menyinkronkan data pensiunan periode Q1 2026 untuk keperluan pelaporan manajemen.',
                'jenis'        => 'informasi',
                'sudah_dibaca' => true,
                'dibaca_pada'  => now()->subDays(1),
                'created_at'   => now()->subDays(2),
                'updated_at'   => now()->subDays(1),
            ];
        }

        if ($keuanganUser) {
            $notifications[] = [
                'user_id'      => $keuanganUser->id,
                'judul'        => 'Antrean Pembayaran Pensiun Baru',
                'pesan'        => 'Terdapat pengajuan pembayaran manfaat pensiun yang siap diproses dan ditransfer ke rekening penerima.',
                'jenis'        => 'pembayaran',
                'sudah_dibaca' => false,
                'dibaca_pada'  => null,
                'created_at'   => now()->subHours(4),
                'updated_at'   => now()->subHours(4),
            ];
            $notifications[] = [
                'user_id'      => $keuanganUser->id,
                'judul'        => 'Penutupan Buku dan Rekonsiliasi Bank',
                'pesan'        => 'Proses rekonsiliasi transfer pembayaran pensiun bulan berjalan telah dibuka. Silakan tinjau status transaksi.',
                'jenis'        => 'informasi',
                'sudah_dibaca' => true,
                'dibaca_pada'  => now()->subDays(3),
                'created_at'   => now()->subDays(4),
                'updated_at'   => now()->subDays(3),
            ];
        }

        foreach ($notifications as $item) {
            Notifikasi::firstOrCreate(
                [
                    'user_id' => $item['user_id'],
                    'judul'   => $item['judul'],
                ],
                $item
            );
        }
    }
}

