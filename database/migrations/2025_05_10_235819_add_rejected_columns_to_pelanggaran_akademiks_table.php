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
        Schema::table('pelanggaran_akademiks', function (Blueprint $table) {
            $table->boolean('rejected_by_admin')->default(false);
            $table->boolean('rejected_by_dosen_wali')->default(false);
            $table->boolean('rejected_by_ketua_jurusan')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggaran_akademiks', function (Blueprint $table) {
            $table->dropColumn(['rejected_by_admin', 'rejected_by_dosen_wali', 'rejected_by_ketua_jurusan']);
        });
    }
};
