<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterRtrwTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_rtrw', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nik')->nullable()->default(16);
            $table->string('nama_lengkap', 100)->nullable()->default('text');
            $table->string('alamat', 100)->nullable()->default('text');
            $table->bigInteger('no_hp')->nullable()->default(13);
            $table->tinyInteger('rt');
            $table->tinyInteger('rw');
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
        Schema::dropIfExists('master_rtrw');
    }
}
