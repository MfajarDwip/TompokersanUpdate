<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateStatusModel extends Model
{
    protected $table = 'pengajuan_surats';

    public function UpdateStatus()
    {
        return $this->join('master_masyarakats', 'pengajuan_surats.id_masyarakat', 'master_masyarakats.id_masyarakat')
            ->select('master_masyarakats.*', 'pengajuan_surats.id_pengajuan');
    }

    protected $fillable = ['pengajuan_surats.status', 'file_pdf', 'info','nomor_surat', 'surat_keluar', 'kode_kecamatan','no_pengantar','keterangan_ditolak'];
}
