<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ElectionType;

class ElectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ElectionType::firstOrCreate(['slug' => 'presiden'], ['name' => 'Presiden', 'slug' => 'presiden']);
        ElectionType::firstOrCreate(['slug' => 'dpr'], ['name' => 'DPR', 'slug' => 'dpr']);
        ElectionType::firstOrCreate(['slug' => 'dpd'], ['name' => 'DPD', 'slug' => 'dpd']);
    }
}
