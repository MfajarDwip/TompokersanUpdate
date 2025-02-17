<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PengajuanModel extends Model
{
    protected $table = 'pengajuan_surats';

    public function pengajuan()
    {
        return $this->join('master_surats', 'pengajuan_surats.id_surat', '=', 'master_surats.id_surat')
        ->join('master_masyarakats', 'master_masyarakats.id_masyarakat', '=', 'pengajuan_surats.id_masyarakat')
        ->join('master_kks', 'master_masyarakats.id_kk', '=', 'master_kks.id_kk')
        ->join('master_akuns', 'master_masyarakats.id_masyarakat', '=', 'master_akuns.id_masyarakat')
        ->select('master_kks.*', 'master_masyarakats.*', 'pengajuan_surats.id_pengajuan', 'pengajuan_surats.status','pengajuan_surats.no_pengantar', 'pengajuan_surats.keterangan', 'pengajuan_surats.created_at', 'pengajuan_surats.image_bukti', 'pengajuan_surats.image_kk', 'pengajuan_surats.image_ktp', 'pengajuan_surats.image_suratnikah', 'pengajuan_surats.image_aktacerai','pengajuan_surats.image_suratkehilangan','pengajuan_surats.image_bidan','pengajuan_surats.image_suratlahir','pengajuan_surats.image_suratkematian','pengajuan_surats.image_aktekelahiran','pengajuan_surats.image_suratizin','pengajuan_surats.image_stnk','pengajuan_surats.image_bpkb','pengajuan_surats.image_sertifikat','pengajuan_surats.image_sppt','pengajuan_surats.image_surattanah','pengajuan_surats.jenis_usaha','pengajuan_surats.tempat_usaha','pengajuan_surats.tahun_kelola','pengajuan_surats.organisasi','pengajuan_surats.pimpinan','pengajuan_surats.alamat_organisasi','pengajuan_surats.nama','pengajuan_surats.tempattgllahir','pengajuan_surats.jenis_kelamin_alm','pengajuan_surats.nikalm','pengajuan_surats.alamatrmh','pengajuan_surats.hari','pengajuan_surats.tgltahunmeninggal','pengajuan_surats.tempat','pengajuan_surats.sebab','pengajuan_surats.usia','pengajuan_surats.agamaank','pengajuan_surats.hubunganalm','pengajuan_surats.barang_hilang','pengajuan_surats.barang_hilang2','pengajuan_surats.barang_hilang3','pengajuan_surats.ruas_jalan','pengajuan_surats.tglpelaksanaan','pengajuan_surats.penutupan','pengajuan_surats.jammulai','pengajuan_surats.jamberakhir','pengajuan_surats.acara','pengajuan_surats.utara','pengajuan_surats.timur','pengajuan_surats.selatan','pengajuan_surats.barat','pengajuan_surats.besartnh','pengajuan_surats.besarbgn','pengajuan_surats.biaya','pengajuan_surats.sekolah','pengajuan_surats.luas','pengajuan_surats.kepemilikan','pengajuan_surats.nomor','pengajuan_surats.letakpersil','pengajuan_surats.anak','pengajuan_surats.nomor_surat','master_surats.id_surat','pengajuan_surats.keterangan_ditolak', 'master_surats.nama_surat', 'pengajuan_surats.kode_kecamatan', 'pengajuan_surats.nomor_surat_tambahan', 'master_akuns.no_hp', 'pengajuan_surats.pengantar_rt', 'pengajuan_surats.pengantar_rw');
    }
    

    use HasFactory;

  
    protected $fillable = [
        'nomor_surat',
        'uuid',
        'no_pengantar',
        'status',
        'keterangan',
        'keterangan_ditolak',
        'file_pdf', // Pastikan ini sesuai dengan nama kolom di database
        'image_bukti',
        'image_kk',
        'image_ktp',
        'image_suratnikah',
        'image_aktacerai',
        'image_suratkehilangan',
        'image_bidan',
        'image_suratlahir',
        'image_suratkematian',
        'image_aktekelahiran',
        'image_suratizin',
        'image_stnk',
        'image_bpkb',
        'image_sertifikat',
        'image_sppt',
        'image_surattanah',
        'pengantar_rt', // Pastikan ini sesuai dengan nama kolom di database
        'pengantar_rw',
        'info',
        'id_masyarakat',
        'id_surat',
        'kode_kecamatan',
        'nomor_surat_tambahan',
    ];
// PengajuanModel.php
public function masyarakat()
{
    return $this->belongsTo(Masyarakat::class, 'id_masyarakat', 'id_masyarakat');
}
}
