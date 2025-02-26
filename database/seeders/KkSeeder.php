<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class KkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_kks')->insert([
            'id' => '665f1f41-ff5b-4a64-89d3-26d16f2882d1',
            'no_kk' => '3508102010020001',
            'alamat' => 'jl. Nangka No.111 Kepuharjo Lumajang',
            'rt' => '1',
            'rw' => '1',
            'kode_pos' => '67316',
            'kelurahan' => 'Kepuharjo',
            'kecamatan' => 'Lumajang',
            'kabupaten' => 'Lumajang',
            'provinsi' => 'Jawa Timur',
            'kk_tgl' => '2023-05-17',
            'created_at' => '2023-05-16 18:01:26',
            'updated_at' => '2023-05-16 18:01:26',
        ],
        );

        DB::table('master_kks')->insert([
            'id' => '665f1f41-ff5b-4a64-89d3-26d16f2882d2',
            'no_kk' => '3508102010020011',
            'alamat' => 'jl. Kelapa Gading No.112 Kepuharjo Lumajang',
            'rt' => '1',
            'rw' => '1',
            'kode_pos' => '67316',
            'kelurahan' => 'Kepuharjo',
            'kecamatan' => 'Lumajang',
            'kabupaten' => 'Lumajang',
            'provinsi' => 'Jawa Timur',
            'kk_tgl' => '2023-05-17',
            'created_at' => '2023-05-16 18:01:26',
            'updated_at' => '2023-05-16 18:01:26',
        ],
        );

        DB::table('master_kks')->insert([
            'id' => '665f1f41-ff5b-4a64-89d3-26d16f2882d3',
            'no_kk' => '3508102010020021',
            'alamat' => 'jl. Jeruk No.113 Kepuharjo Lumajang',
            'rt' => '1',
            'rw' => '1',
            'kode_pos' => '67316',
            'kelurahan' => 'Kepuharjo',
            'kecamatan' => 'Lumajang',
            'kabupaten' => 'Lumajang',
            'provinsi' => 'Jawa Timur',
            'kk_tgl' => '2023-05-17',
            'created_at' => '2023-05-16 18:01:26',
            'updated_at' => '2023-05-16 18:01:26',
        ],
        );

        DB::table('master_kks')->insert([
            'id' => '665f1f41-ff5b-4a64-89d3-26d16f2882d4',
            'no_kk' => '3508102010020031',
            'alamat' => 'jl. Semangka No.114 Kepuharjo Lumajang',
            'rt' => '1',
            'rw' => '1',
            'kode_pos' => '67316',
            'kelurahan' => 'Kepuharjo',
            'kecamatan' => 'Lumajang',
            'kabupaten' => 'Lumajang',
            'provinsi' => 'Jawa Timur',
            'kk_tgl' => '2023-05-17',
            'created_at' => '2023-05-16 18:01:26',
            'updated_at' => '2023-05-16 18:01:26',
        ],
        );

        DB::table('master_kks')->insert([
            'id' => '665f1f41-ff5b-4a64-89d3-26d16f2882d5',
            'no_kk' => '3508102010020041',
            'alamat' => 'jl. Durian No.115 Kepuharjo Lumajang',
            'rt' => '1',
            'rw' => '1',
            'kode_pos' => '67316',
            'kelurahan' => 'Kepuharjo',
            'kecamatan' => 'Lumajang',
            'kabupaten' => 'Lumajang',
            'provinsi' => 'Jawa Timur',
            'kk_tgl' => '2023-05-17',
            'created_at' => '2023-05-16 18:01:26',
            'updated_at' => '2023-05-16 18:01:26',
        ],
        );
    }
}
