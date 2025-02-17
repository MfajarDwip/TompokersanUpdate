<?php

namespace Database\Seeders;

use App\Models\MobileBeritaModel;
use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterSuratModel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([

        //     //input_berita_seeder::class,
        //     KkSeeder::class,
        //     MasyarakatSeeder::class,
        // ]);
        //  \App\Models\User::factory(100)->create();
        //  \App\Models\master_kk::factory(10)->create();
        MobileMasterKksModel::create([
            'no_kk' => '3509212504030000',
            'alamat' => 'Jember',
            'rt' => '1',
            'rw' => '1',
            'kode_pos' => '9123',
            'kelurahan' => 'wirolegi',
            'kecamatan' => 'sumbersari',
            'kabupaten' => 'jember',
            'provinsi' => 'jawa timur',
            'kk_tgl' => '',
        ]);
        MobileMasterKksModel::create([
            'no_kk' => '3509212504040000',
            'alamat' => 'Jember',
            'rt' => '1',
            'rw' => '1',
            'kode_pos' => '9123',
            'kelurahan' => 'wirolegi',
            'kecamatan' => 'sumbersari',
            'kabupaten' => 'jember',
            'provinsi' => 'jawa timur',
            'kk_tgl' => '',
        ]);
        MobileBeritaModel::create([
            'judul' => 'Begini Upaya Pemda Tekan Angka Pengangguran di Lumajang',
            'sub_title' => 'Lumajang, 13 Juni 2023',
            'deskripsi' => 'Pemerintah Kabupaten (Pemkab) Lumajang, Jawa Timur terus berupaya mengurangi angka pengangguran dari pengaruh situasi ketidakpastian ekonomi global maupun nasional.

            Saat dimintai keterangan di kantornya, Selasa (13/6/2023), Bupati Lumajang Thoriqul Haq (Cak Thoriq) mengatakan, bahwa Tingkat Pengangguran Terbuka (TPT) Lumajang pada 2022 tercatat sebesar 4,97 persen, angka itu naik 1,46 persen dibandingkan tahun 2021 yang mencapai 3,51 persen.
            
            "Kenaikan ini bisa jadi dipicu kondisi perekonomian global dan nasional yang masih belum stabil, sehingga peluang kerja belum tersedia sepenuhnya," kata dia.
            
            Cak Thoriq juga mengatakan, adapun beberapa intervensi pemerintah untuk mengurangi angka pengangguran, beberapa diantaranya menciptakan lapangan pekerjaan melalui program padat karya dan pemberian bantuan sosial kepada masyarakat yang rentan akibat dampak kondisi perekonomian yang masih belum stabil.
            
            "Ini merupakan Intervensi pemerintah untuk menekan angka pengangguran," ujarnya.
            
            Sementara itu, dilihat dari sisi jumlah angkatan kerja, pada tahun 2022 jumlah angkatan kerja di Lumajang mencapai sebanyak 586.536 orang, angka itu naik 32.218 orang dibandingkan tahun 2021.
            
            "Sejalan dengan kenaikan jumlah angkatan kerja, Tingkat Partisipasi Angkatan Kerja (TPAK) tahun 2022 tercatat sebesar 66,75 persen, angka itu mengalami kenaikan 3,56 persen dari tahun 2021 yang mencapai 66,19 persen," pungkasnya. (Kominfo-lmj/Ard)',
            'image' => '1685315576.jpeg'
        ]);
        MobileBeritaModel::create([
            'judul' => 'Mahasiswa di Lumajang Diajak Berperan Aktif Sukseskan Pemilu 2024',
            'sub_title' => 'Lumajang, 13 Juni 2023',
            'deskripsi' => 'Wakil Bupati Lumajang Indah Amperawati (Bunda Indah) mengajak para mahasiswa untuk turut berperan aktif dalam penyelenggaraan Pemilihan Umum (Pemilu) Serentak Tahun 2024.

            Hal tersebut disampaikannya dalam Seminar Kebangsaan dengan tema "Peran Mahasiswa Menghadapi Pemilu 2024" yang diselenggarakan Ikatan Mahasiswa Muhammadiyah, di Gedung Dakwah Muhammadiyah Lumajang, Selasa (13/6/2023).
            
            Bunda Indah juga menyampaikan, bahwa peran mahasiswa sangatlah penting terhadap suksesnya penyelenggaraan Pemilu Serentak Tahun 2023. Mahasiswa bisa turut mengawal penyelenggaraan proses Pemilu untuk meminimalisir kecurangan yang terjadi.
            
            "Ini memerlukan sebuah sistem yang bagus untuk kesuksesan Pemilu. Ini peran mahasiswa sangat dibutuhkan untuk membantu persiapan penyelenggaraan sampai pelaksanaan pemilu," ujarnya.
            
            Selain itu, mahasiswa juga bisa berperan membatu sosialisasi kepada masyarakat untuk tidak apatis apalagi sampai tida memilih atau golput.
            
            "Jangan apatis, ini bahaya. Ini momen penting untuk memilih, untuk memilih pempimpin, mahasiswa bisa membantu bagaimana merubah mindset masyarakat dalam hajatan Pemilu, ikut menyuarakan positifnya politik, agar ada dampak yang dihasilkan," katanya.
            
            Dalam seminar tersebut, selain Wakil Bupati Lumajang, Indah Amperawati yang menjadi narasumber, turut hadir pula menjadi narasumber dari Banwaslu Kabupaten Lumajang, Akhmad Mujjadid. (Kominfo-lmj/Fd)',
            'image' => '1685315622.jpeg'
        ]);
        MobileMasterSuratModel::create([
            'id_surat' => '1',
            'nama_surat' => 'Tidak Mampu' ,
            'image' => '1685315194.png'
        ]);
        MobileMasterSuratModel::create([
            'id_surat' => '2',
            'nama_surat' => 'Domisili' ,
            'image' => '1685315044.png'
        ]);
        MobileMasterSuratModel::create([
            'id_surat' => '3',
            'nama_surat' => 'Berkelakuan Baik' ,
            'image' => '1685315232.png'
        ]);
        MobileMasterSuratModel::create([
            'id_surat' => '4',
            'nama_surat' => 'Tidak Mampu' ,
            'image' => '1685315194.png'
        ]);
        MobileMasterSuratModel::create([
            'id_surat' => '5',
            'nama_surat' => 'Domisili' ,
            'image' => '1685315044.png'
        ]);
        MobileMasterSuratModel::create([
            'id_surat' => '6',
            'nama_surat' => 'Berkelakuan Baik' ,
            'image' => '1685315232.png'
        ]);
        MobileMasterSuratModel::create([
            'id_surat' => '7',
            'nama_surat' => 'Domisili' ,
            'image' => '1685315044.png'
        ]);
        MobileMasterSuratModel::create([
            'id_surat' => '8',
            'nama_surat' => 'Berkelakuan Baik' ,
            'image' => '1685315232.png'
        ]);
    }
}
