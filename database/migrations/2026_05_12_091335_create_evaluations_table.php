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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('magang_applications')->cascadeOnDelete();
            $table->foreignId('dpl_user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('kedisiplinan');
            $table->integer('teknis');
            $table->integer('komunikasi');
            $table->integer('inisiatif');
            $table->decimal('nilai_akhir', 5, 2);
            $table->text('komentar')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
