<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_surats')->insert([
            'id_surat' => '1',
            'nama_surat' => 'TIDAK MAMPU',
            'image' => 'sktm.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '2',
            'nama_surat' => 'BELUM MENIKAH',
            'image' => 'belumnikah.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '3',
            'nama_surat' => 'BEPERGIAN',
            'image' => 'bepergian.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '4',
            'nama_surat' => 'BBERKELAKUAK BAIK',
            'image' => 'skck.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '5',
            'nama_surat' => 'DOMISILI',
            'image' => 'domisili.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '6',
            'nama_surat' => 'IDENTITAS',
            'image' => 'identitas.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '7',
            'nama_surat' => 'KEMATIAN',
            'image' => 'kematian.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '8',
            'nama_surat' => 'KENAL LAHIR',
            'image' => 'kenallahir.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '9',
            'nama_surat' => 'KERAMAIAN',
            'image' => 'keramaian.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '10',
            'nama_surat' => 'PINDAH',
            'image' => 'pindah.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );

        DB::table('master_surats')->insert([
            'id_surat' => '11',
            'nama_surat' => 'USAHA',
            'image' => 'usaha.png',
            'created_at' => '2023-05-18 14:13:50',
            'updated_at' => '2023-05-18 14:13:50',
        ]
        );
    }
}
