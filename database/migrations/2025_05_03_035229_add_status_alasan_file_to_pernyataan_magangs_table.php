<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pernyataan_magangs', function (Blueprint $table) {
        $table->string('status')->nullable(); // e.g. approved / rejected
        $table->text('alasan')->nullable();
        $table->string('file_pdf')->nullable();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pernyataan_magangs', function (Blueprint $table) {
            $table->dropColumn(['status', 'alasan', 'file_pdf']);
        });
    }
};
