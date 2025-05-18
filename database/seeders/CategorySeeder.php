<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert([
            ['id' => 1, 'name' => 'Ekonomi', 'slug' => Str::random(10)],
            ['id' => 2, 'name' => 'Bisnis', 'slug' => Str::random(10)],
            ['id' => 3, 'name' => 'Eksekutif', 'slug' => Str::random(10)],
            ['id' => 4, 'name' => 'VIP', 'slug' => Str::random(10)],
        ]);
    }
}
