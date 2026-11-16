<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin HRD',
                'email'    => 'hrd@jasamarga.co.id',
                'password' => bcrypt('password123'),
                'role'     => 'hrd',
            ],
            [
                'name'     => 'Admin Keuangan',
                'email'    => 'keuangan@jasamarga.co.id',
                'password' => bcrypt('password123'),
                'role'     => 'keuangan',
            ],
            [
                'name'     => 'Budi Santoso',
                'email'    => 'pensiunan@jasamarga.co.id',
                'password' => bcrypt('password123'),
                'role'     => 'pensiunan',
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
