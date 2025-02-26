<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class MobileMasterMasyarakatModel extends Model
{
    use HasFactory;

    // protected $keyType = 'uuid';

    protected $table = 'master_masyarakats';

    public function masyarakat()
    {
        return $this->belongsTo(MobileMasterKksModel::class, 'id_kk', 'id_kk');
    }

    protected $fillable = [
        'no_kk', 'nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir',
        'tgl_lahir', 'agama', 'pendidikan', 'pekerjaan', 'golongan_darah', 'status_perkawinan', 'tgl_perkawinan',
        'status_keluarga', 'kewarganegaraan', 'no_paspor', 'no_kitap', 'nama_ayah', 'nama_ibu', 'id_kk', 'created_at', 'updated_at',
    ];

    protected $primaryKey = 'id_masyarakat'; 


    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($model) {
    //         if (empty($model->{$model->getKeyName()})) {
    //             $model->{$model->getKeyName()} = Str::uuid()->toString();
    //         }
    //     });
    // }

    // /**
    //  * Kita override getIncrementing method
    //  *
    //  * Menonaktifkan auto increment
    //  */
    // public function getIncrementing()
    // {
    //     return false;
    // }

    // /**
    //  * Kita override getKeyType method
    //  *
    //  * Memberi tahu laravel bahwa model ini menggunakan primary key bertipe string
    //  */
    // public function getKeyType()
    // {
    //     return 'string';
    // }

    public function akun()
    {
        return $this->hasOne(MobileMasterAkunModel::class, 'id_masyarakat', 'id_masyarakat');
    }

    public function kks()
    {
        return $this->belongsTo(MobileMasterKksModel::class, 'id_kk', 'id_kk');
    }


    public function pengajuan_surats()
    {
        return $this->hasMany(MobilePengajuanSuratModel::class, 'id_masyarakat', 'id_masyarakat');
    }
}
