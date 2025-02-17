<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMasterAkunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_akuns', function ($table) {
            $table->id('akun_id');
            $table->uuid('uuid');
            $table->string('password', 255)->nullable()->default('text');
            $table->String('no_hp')->nullable()->default(13);
            $table->string('role', 12)->nullable()->default('text');
            $table->string('fcm_token')->nullable();
            $table->timestamps();
            $table->bigInteger('id_masyarakat')->unsigned();
            $table->Foreign('id_masyarakat')->references('id_masyarakat')->on('master_masyarakats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_akuns');
    }
}
