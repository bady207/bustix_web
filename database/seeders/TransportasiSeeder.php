<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransportasiSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        DB::table('transportasi')->insert([
            ['category_id' => 1, 'name' => 'Sinar Jaya', 'kode' => 'SJ001', 'jumlah' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 1, 'name' => 'Haryanto', 'kode' => 'HR002', 'jumlah' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Rosalia Indah', 'kode' => 'RI003', 'jumlah' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'name' => 'Efisiensi', 'kode' => 'EF004', 'jumlah' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'name' => 'Lorena', 'kode' => 'LR005', 'jumlah' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'name' => 'Harapan Jaya', 'kode' => 'HJ006', 'jumlah' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'name' => 'Kramat Djati', 'kode' => 'KD007', 'jumlah' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'name' => 'Big Bird', 'kode' => 'BB008', 'jumlah' => 9, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
