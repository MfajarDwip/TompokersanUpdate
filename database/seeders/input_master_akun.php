<?php

namespace Database\Seeders;

use App\Models\master_akun;
use Illuminate\Database\Seeder;

class input_master_akun extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        master_akun::truncate();
        master_akun::create([
            // 'id' => 2,
            'nama' => 'Edy',
            'password' => '123',
            // 'role'=>'admin',

        ]);
        // master_akun::table('master_akuns')->insert([
        //     'id' => 1,
        //     'name'=> 'Edy',
        //     'password'=>'123',
        //     'role'=>'admin',
        //     'created_at' => now(),
        //     // 'nik'=>3508102010020007,
        // ]);
    }
}
