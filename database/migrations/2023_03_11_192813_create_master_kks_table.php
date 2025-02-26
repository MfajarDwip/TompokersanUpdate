<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterKksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_kks', function (Blueprint $table) {
            $table->id('id_kk');
            $table->uuid('uuid');
            $table->bigInteger('no_kk')->nullable()->default(12);
            $table->unique('no_kk');
            $table->string('alamat', 100)->nullable()->default('text');
            $table->tinyInteger('rt');
            $table->tinyInteger('rw');
            $table->integer('kode_pos');
            $table->string('kelurahan', 60)->nullable()->default('text');
            $table->string('kecamatan', 60)->nullable()->default('text');
            $table->string('kabupaten', 60)->nullable()->default('text');
            $table->string('provinsi', 60)->nullable()->default('text');
            $table->string('kk_tgl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_kks');
        // $table->smallInteger('kode_pos')->change();
    }
}
