<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
    User::firstOrCreate(
        ['email' => 'admin@pemilu.com'],
        [
            'name'           => 'Admin KPU',
            'nik'            => '0000000000000000',
            'password'       => Hash::make('admin123'),
            'role'           => 'admin',
            'voted_presiden' => false,
            'voted_dpr'      => false,
            'voted_dpd'      => false,
        ]
    );

    // Contoh warga
    User::firstOrCreate(
        ['email' => 'budi@gmail.com'],
        [
            'name'           => 'Budi Santoso',
            'nik'            => '3201010101010001',
            'password'       => Hash::make('budi123'),
            'role'           => 'user',
            'voted_presiden' => false,
            'voted_dpr'      => false,
            'voted_dpd'      => false,
        ]
    );
    }
}
