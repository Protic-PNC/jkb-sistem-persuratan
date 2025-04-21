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
        Schema::create('pelanggaran_akademiks', function (Blueprint $table) {
            $table->id('noSurat');
            $table->string('nama_mhs');
            $table->string('nama_pelapor');
            $table->string('nama_dosen_wali');
            $table->string('nama_ketua_jurusan');
            $table->string('username');
            $table->string('semester');
            $table->string('kelas_id');
            $table->enum('peringatan', ['lisan', 'tertulis']);
            $table->string('hari');
            $table->date('tglSurat');
            $table->string('status_surat');
            $table->string('pasal');
            $table->text('isi_pasal');
            $table->integer('jumlah_peringatan');
            $table->string('ttd_mahasiswa')->nullable(); 
            $table->string('ttd_pelapor')->nullable(); 
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
        Schema::dropIfExists('pelanggaran_akademiks');
    }
};
