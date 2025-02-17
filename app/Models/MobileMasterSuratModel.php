<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileMasterSuratModel extends Model
{
    protected $table = 'master_surats';

    protected $primaryKey = 'id_surat';

    protected $fillable = ['id_surat', 'nama_surat', 'image'];

    public function pengajuan_surats()
    {
        return $this->hasMany(MobilePengajuanSuratModel::class, 'id_surat', 'id_surat');
    }
}
