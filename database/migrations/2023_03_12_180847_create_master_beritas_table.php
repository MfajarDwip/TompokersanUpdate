<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterBeritasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_beritas', function (Blueprint $table) {
            $table->id();
            $table->text('judul')->nullable()->default('text');
            $table->text('sub_title')->nullable()->default('text');
            $table->text('deskripsi')->nullable()->default('text');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('master_beritas');
    }
}
