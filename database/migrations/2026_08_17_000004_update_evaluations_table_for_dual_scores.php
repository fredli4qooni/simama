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
            $table->decimal('nilai_dpl', 5, 2)->nullable()->after('dpl_user_id');
            $table->decimal('nilai_instansi', 5, 2)->nullable()->after('nilai_dpl');
            $table->integer('kedisiplinan')->nullable()->change();
            $table->integer('teknis')->nullable()->change();
            $table->integer('komunikasi')->nullable()->change();
            $table->integer('inisiatif')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['nilai_dpl', 'nilai_instansi']);
        });
    }
};
