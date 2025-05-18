<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('kursi')->nullable();
            $table->timestamp('waktu');
            $table->integer('total');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('rute_id')->cascadeOnDelete();
            $table->unsignedBigInteger('penumpang_id')->cascadeOnDelete();
            $table->unsignedBigInteger('petugas_id')->nullable()->cascadeOnDelete();
            $table->timestamps();

            $table->foreign('rute_id')->references('id')->on('rute')->onDelete('cascade');
            $table->foreign('penumpang_id')->references('id')->on('users');
            $table->foreign('petugas_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanan');
    }
}
