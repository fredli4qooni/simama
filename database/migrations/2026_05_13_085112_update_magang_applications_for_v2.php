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
        Schema::table('magang_applications', function (Blueprint $table) {
            $table->foreignId('prodi_id')->nullable()->constrained('prodis');
            $table->enum('jenis_magang', ['prodi', 'mandiri'])->default('prodi');
            $table->text('alamat_instansi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
