<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class MobileMasterKksModel extends Model
{
    protected $table = 'master_kks';
    protected $primaryKey = 'id_kk';
    protected $fillable = [
        'no_kk',
        'id_kk',
        'alamat', 'rt', 'rw', 'kode_pos', 'kelurahan', 'kecamatan', 'kabupaten',
        'provinsi', 'kk_tgl', 'created_at', 'updated_at',
    ];

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
    // protected $primarykey = 'no_kk';

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($model) {
    //         if (empty($model->{$model->getKeyName()})) {
    //             $model->{$model->getKeyName()} = Str::uuid()->toString();
    //         }
    //     });
    // }

    // public function getIncrementing()
    // {
    //     return false;
    // }

    // public function getKeyType()
    // {
    //     return 'string';
    // }

    public function masyarakat()
    {
        return $this->hasMany(MobileMasterMasyarakatModel::class, 'id_kk', 'id_kk');
    }
}
