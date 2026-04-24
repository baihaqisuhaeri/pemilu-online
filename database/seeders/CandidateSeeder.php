<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Candidate;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Candidate::insert([
        ['name' => 'Paslon 1 - Andi & Budi',   'nomor_urut' => 1, 'visi_misi' => 'Visi misi paslon 1', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Paslon 2 - Citra & Deni',  'nomor_urut' => 2, 'visi_misi' => 'Visi misi paslon 2', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Paslon 3 - Eka & Fajar',   'nomor_urut' => 3, 'visi_misi' => 'Visi misi paslon 3', 'created_at' => now(), 'updated_at' => now()],
    ]);
    }
}
