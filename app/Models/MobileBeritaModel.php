<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileBeritaModel extends Model
{
    protected $table = 'master_beritas';

    protected $fillable = ['judul', 'sub_title', 'deskripsi', 'image'];
}
