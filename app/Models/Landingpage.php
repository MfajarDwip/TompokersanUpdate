<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landingpage extends Model
{
    protected $table = 'landings'; 
    protected $fillable = [
        'nama_website',
        'judul_home',
        'deskripsi_home',
        'judul_tentang',
        'link_download',
        'tentang_aplikasi',
        'email_kelurahan',
        'no_telp',
        'alamat_kelurahan',
        'video_url',
        'token'
    ];
}
