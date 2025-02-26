<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Str;

class MasterKKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FacadesDB::table('master_kks')->insert([
            'name' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
