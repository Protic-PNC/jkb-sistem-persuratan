<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pernyataan_magangs', function (Blueprint $table) {
            $table->id('noSurat');
            $table->string('nama_ortu');
            $table->text('alamat');
            $table->text('no_telp');
            $table->string('nama_mhs');
            $table->string('npm')->unique();
            $table->string('jurusan');
            $table->string('perguruan_tinggi');
            $table->date('tglSurat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pernyataan_magangs');
    }
};
