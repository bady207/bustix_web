<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RuteSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // Daftar rute dari Bandung ke berbagai tujuan
        $rute_list = [
            ['tujuan' => 'Bandung - Jakarta', 'start' => 'Bandung', 'end' => 'Jakarta', 'harga' => 100000, 'jam' => '08:00:00'],
            ['tujuan' => 'Bandung - Surabaya', 'start' => 'Bandung', 'end' => 'Surabaya', 'harga' => 300000, 'jam' => '14:00:00'],
            ['tujuan' => 'Bandung - Yogyakarta', 'start' => 'Bandung', 'end' => 'Yogyakarta', 'harga' => 250000, 'jam' => '07:00:00'],
            ['tujuan' => 'Bandung - Semarang', 'start' => 'Bandung', 'end' => 'Semarang', 'harga' => 200000, 'jam' => '11:00:00'],
            ['tujuan' => 'Bandung - Bali', 'start' => 'Bandung', 'end' => 'Bali', 'harga' => 500000, 'jam' => '16:00:00'],
        ];

        // Daftar kategori transportasi yang tersedia
        $kategori_transportasi = [
            ['id' => 1, 'nama' => 'Ekonomi'],
            ['id' => 2, 'nama' => 'Bisnis'],
            ['id' => 3, 'nama' => 'Eksekutif'],
            ['id' => 4, 'nama' => 'VIP'],
        ];

        // Simpan rute ke database
        foreach ($rute_list as $rute) {
            foreach ($kategori_transportasi as $kategori) {
                DB::table('rute')->insert([
                    'tujuan'          => $rute['tujuan'],
                    'start'           => $rute['start'],
                    'end'             => $rute['end'],
                    'harga'           => $rute['harga'] + ($kategori['id'] * 50000), // Harga naik sesuai kategori
                    'jam'             => $rute['jam'],
                    'transportasi_id' => $kategori['id'], // Gunakan ID kategori sebagai transportasi_id
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            }
        }
    }
}
