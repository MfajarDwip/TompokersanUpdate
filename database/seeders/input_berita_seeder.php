<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class input_berita_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_beritas')->insert([
            'id_berita' => 2,
            'judul' => Str::random(10),
            'sub_title' => Str::random(10),
            'deskripsi' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
