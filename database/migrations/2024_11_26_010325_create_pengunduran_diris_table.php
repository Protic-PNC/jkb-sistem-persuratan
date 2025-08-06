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
        Schema::create('pengunduran_diris', function (Blueprint $table) {
            $table->id('noSurat');
            $table->string('nama_mhs');
            $table->string('nama_dosen_wali');
            $table->string('nama_ketua_jurusan');
            $table->string('username');
            $table->string('semester');
            $table->string('kelas_id');
            $table->string('jurusan');
            $table->string('no_telp');
            $table->string('alamat');
            $table->date('tglSurat');
            $table->string('status_surat');
            $table->text('alasan'); 
            $table->string('ttd_mahasiswa')->nullable(); 
            $table->string('ttd_dosen_wali')->nullable(); 
            $table->string('ttd_ketua_jurusan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengunduran_diris');
    }
};
