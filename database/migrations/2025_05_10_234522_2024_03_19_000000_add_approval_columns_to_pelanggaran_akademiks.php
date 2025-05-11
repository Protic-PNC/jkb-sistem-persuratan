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
            $table->boolean('approved_by_admin')->default(false);
            $table->boolean('approved_by_dosen_wali')->default(false);
            $table->boolean('approved_by_ketua_jurusan')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggaran_akademiks', function (Blueprint $table) {
            $table->dropColumn([
                'approved_by_admin',
                'approved_by_dosen_wali',
                'approved_by_ketua_jurusan'
            ]);
        });
    }
};
