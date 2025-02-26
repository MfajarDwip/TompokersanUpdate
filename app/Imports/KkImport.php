<?php

namespace App\Imports;

use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterMasyarakatModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;

class KkImport implements ToCollection
{
    protected $lastIdKk;

    public function __construct()
    {
        $this->lastIdKk = MobileMasterKksModel::max('id_kk') ?? 0;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $indexKe = 1;
        $notifikasi = []; 
        foreach ($collection as $row) {
            $status = !empty($row[8]) ? $row[8] : null;

            if ($indexKe > 1 && $status === 'KK') {
                $noKk = !empty($row[2]) ? $row[2] : null;

                if (MobileMasterKksModel::where('no_kk', $noKk)->exists()) {
                    // Tambahkan notifikasi bahwa no_kk sudah ada
                    $notifikasi[] = "Data dengan No KK {$noKk} sudah ada dan diabaikan.";
                    continue;
                }

                $this->lastIdKk++;
                $dataKks = [
                    'id_kk'      => $this->lastIdKk,
                    'no_kk'      => $noKk,
                    'alamat'     => !empty($row[4]) ? $row[4] : null,
                    'rt'         => !empty($row[5]) ? $row[5] : null,
                    'rw'         => !empty($row[6]) ? $row[6] : null,
                    'kode_pos'   => '67316',
                    'kelurahan'  => 'Tompokersan',
                    'provinsi'   => 'Jawa Timur',
                    'kabupaten'  => 'Lumajang',
                    'kecamatan'  => 'Lumajang',
                    'kk_tgl'     => now(),
                ];

                try {
                    MobileMasterKksModel::create($dataKks);

                    $nik = !empty($row[1]) ? $row[1] : null;
                    $namaLengkap = !empty($row[3]) ? $row[3] : null;

                    MobileMasterMasyarakatModel::create([
                        'id_kk' => $this->lastIdKk,
                        'nik' => $nik,
                        'nama_lengkap' => $namaLengkap,
                        'status_keluarga' => 'Kepala Keluarga',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }

            $indexKe++;
        }

        // Jika ada notifikasi duplikat, simpan di session
        if (!empty($notifikasi)) {
            Session::flash('import_notifications', $notifikasi);
        }
    }
}
