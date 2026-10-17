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
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('berkas_nilai_instansi')->nullable()->after('komentar');
            $table->string('berkas_nilai_dpl')->nullable()->after('berkas_nilai_instansi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['berkas_nilai_instansi', 'berkas_nilai_dpl']);
        });
    }
};
