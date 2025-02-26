<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RwModel extends Model
{
    protected $table = 'master_kks';

    public function index()
    {
        return $this->join('master_masyarakats', 'master_kks.id', '=', 'master_masyarakats.id')
            ->join('master_akuns', 'master_akuns.id_masyarakat', '=', 'master_akuns.id_masyarakat')
            ->select('master_kks.*', 'master_masyarakats.*', 'master_akuns.*');
    }
}
