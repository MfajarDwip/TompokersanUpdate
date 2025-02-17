<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterMasyarakatModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MasImport implements ToCollection
{
    protected $lastIdMasyarakat;

    public function __construct()
    {
        $this->lastIdMasyarakat = MobileMasterMasyarakatModel::max('id_masyarakat') ?? 0;
    }

    public function collection(Collection $collection)
    {
        $indexKe = 1;
        $currentIdKk = null;

        foreach ($collection as $row) {
            $status = !empty($row[8]) ? $row[8] : null;

            if ($indexKe > 1) {
                if ($status === 'KK') {
                    $noKk = !empty($row[2]) ? $row[2] : null;
                    $existingKk = MobileMasterKksModel::where('no_kk', $noKk)->first();

                    if ($existingKk) {
                        $currentIdKk = $existingKk->id_kk;
                    }
                }

                if ($status === 'ANGGOTA' && $currentIdKk !== null) {
                    try {
                        $tanggalLahirExcel = !empty($row[10]) ? $row[10] : null;
                        $tanggalKawinExcel = !empty($row[17]) ? $row[17] : null;
                        $tanggalLahir = $tanggalLahirExcel ? $this->convertExcelDateToDate($tanggalLahirExcel) : null;
                        $tanggalKawin = $tanggalKawinExcel ? $this->convertExcelDateToDate($tanggalKawinExcel) : null;

                        // Check if record with the same NIK already exists
                        $existingMasyarakat = MobileMasterMasyarakatModel::where('nik', $row[1])->first();

                        if ($existingMasyarakat) {
                            // Update the existing record instead of creating a new one
                            $existingMasyarakat->update([
                                'id_kk'            => $currentIdKk,
                                'nama_lengkap'     => !empty($row[3]) ? $row[3] : null,
                                'jenis_kelamin'    => !empty($row[7]) ? $row[7] : null,
                                'tempat_lahir'     => !empty($row[9]) ? $row[9] : null,
                                'tgl_lahir'        => $tanggalLahir, 
                                'agama'            => !empty($row[12]) ? $row[12] : null,
                                'pendidikan'       => !empty($row[13]) ? $row[13] : null,
                                'pekerjaan'        => !empty($row[14]) ? $row[14] : null, 
                                'golongan_darah'   => !empty($row[15]) ? $row[15] : null, 
                                'status_perkawinan'=> !empty($row[16]) ? $row[16] : null,
                                'tgl_perkawinan'   => $tanggalKawin,
                                'status_keluarga'  => !empty($row[18]) ? $row[18] : null,
                                'kewarganegaraan'  => !empty($row[19]) ? $row[19] : null,
                                'no_paspor'        => !empty($row[20]) ? $row[20] : null,
                                'no_kitap'         => !empty($row[21]) ? $row[21] : null, 
                                'nama_ayah'        => !empty($row[22]) ? $row[22] : null,
                                'nama_ibu'         => !empty($row[23]) ? $row[23] : null,
                                'updated_at'       => now(),
                            ]);
                        } else {
                            // If no existing record, create a new one
                            MobileMasterMasyarakatModel::create([
                                'id_kk'            => $currentIdKk,
                                'nik'              => !empty($row[1]) ? $row[1] : null,
                                'nama_lengkap'     => !empty($row[3]) ? $row[3] : null,
                                'jenis_kelamin'    => !empty($row[7]) ? $row[7] : null,
                                'tempat_lahir'     => !empty($row[9]) ? $row[9] : null,
                                'tgl_lahir'        => $tanggalLahir, 
                                'agama'            => !empty($row[12]) ? $row[12] : null,
                                'pendidikan'       => !empty($row[13]) ? $row[13] : null,
                                'pekerjaan'        => !empty($row[14]) ? $row[14] : null, 
                                'golongan_darah'   => !empty($row[15]) ? $row[15] : null, 
                                'status_perkawinan'=> !empty($row[16]) ? $row[16] : null,
                                'tgl_perkawinan'   => $tanggalKawin,
                                'status_keluarga'  => !empty($row[18]) ? $row[18] : null,
                                'kewarganegaraan'  => !empty($row[19]) ? $row[19] : null,
                                'no_paspor'        => !empty($row[20]) ? $row[20] : null,
                                'no_kitap'         => !empty($row[21]) ? $row[21] : null, 
                                'nama_ayah'        => !empty($row[22]) ? $row[22] : null,
                                'nama_ibu'         => !empty($row[23]) ? $row[23] : null,
                                'created_at'       => now(),
                                'updated_at'       => now(),
                            ]);
                        }
                    } catch (\Throwable $th) {
                        throw $th;
                    }
                }
            }
            $indexKe++;
        }
    }

    private function convertExcelDateToDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate));
        }
        return null;
    }
}