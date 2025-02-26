<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class MobileMasterAkunModel extends Model
{
    use HasApiTokens;

    protected $table = 'master_akuns';
    protected $primaryKey = 'akun_id';

    protected $fillable = [
        'akun_id', 'uuid', 'password', 'no_hp', 'role', 'fcm_token', 'id_masyarakat', 'device_name', 'device_version', 'device_identifier',
    ];
    protected $foreignKey = 'id_masyarakat'; // Nama kolom kunci asing pada tabel master_akuns


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

    public function user()
    {
        return $this->hasOne(MobileMasterMasyarakatModel::class, 'id_masyarakat', 'id_masyarakat')
            ->join('master_kks', 'master_kks.id_kk', '=', 'master_masyarakats.id_kk');
    }

    public function masyarakat()
    {
        return $this->belongsTo(MobileMasterMasyarakatModel::class, 'id_masyarakat', 'id_masyarakat');
    }

}
