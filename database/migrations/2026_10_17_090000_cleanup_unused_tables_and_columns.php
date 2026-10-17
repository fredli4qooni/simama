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
        // 1. Drop unused table consultations
        Schema::dropIfExists('consultations');

        // 2. Drop unused surat_tugas_path from dpl_assignments
        if (Schema::hasColumn('dpl_assignments', 'surat_tugas_path')) {
            Schema::table('dpl_assignments', function (Blueprint $table) {
                $table->dropColumn('surat_tugas_path');
            });
        }

        // 3. Drop obsolete criteria columns from evaluations
        Schema::table('evaluations', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['kedisiplinan', 'teknis', 'komunikasi', 'inisiatif'] as $col) {
                if (Schema::hasColumn('evaluations', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('consultations')) {
            Schema::create('consultations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('magang_application_id')->constrained('magang_applications')->cascadeOnDelete();
                $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('dpl_assignments', 'surat_tugas_path')) {
            Schema::table('dpl_assignments', function (Blueprint $table) {
                $table->string('surat_tugas_path')->nullable()->after('assigned_at');
            });
        }

        Schema::table('evaluations', function (Blueprint $table) {
            $table->integer('kedisiplinan')->nullable()->after('nilai_instansi');
            $table->integer('teknis')->nullable()->after('kedisiplinan');
            $table->integer('komunikasi')->nullable()->after('teknis');
            $table->integer('inisiatif')->nullable()->after('komunikasi');
        });
    }
};
