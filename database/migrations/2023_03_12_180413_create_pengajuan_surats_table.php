<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanSuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_surats', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->uuid('uuid');
            $table->String('nomor_surat')->nullable();
            $table->String('no_pengantar')->nullable();
            $table->string('status', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_ditolak')->nullable();
            $table->timestamps();
            $table->string('file_pdf')->nullable();
            $table->string('image_bukti')->nullable();
            $table->string('image_kk')->nullable();
            $table->string('image_ktp')->nullable();
            $table->string('image_suratnikah')->nullable();
            $table->string('image_aktacerai')->nullable();
            $table->string('image_suratkehilangan')->nullable();
            $table->string('image_bidan')->nullable();
            $table->string('image_suratlahir')->nullable();
            $table->string('image_suratkematian')->nullable();
            $table->string('image_aktekelahiran')->nullable();
            $table->string('image_suratizin')->nullable();
            $table->string('image_stnk')->nullable();
            $table->string('image_bpkb')->nullable();
            $table->string('image_sertifikat')->nullable();
            $table->string('image_sppt')->nullable();
            $table->string('image_surattanah')->nullable();
            $table->enum('info', ['active', 'non_active']);
            $table->bigInteger('id_masyarakat')->unsigned();
            $table->Foreign('id_masyarakat')->references('id_masyarakat')->on('master_masyarakats');
            $table->smallInteger('id_surat');
            $table->Foreign('id_surat')->references('id_surat')->on('master_surats');
            $table->string('kode_kecamatan');
            $table->string('nomor_surat_tambahan');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_surats');
    }
}
