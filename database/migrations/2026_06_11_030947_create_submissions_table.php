<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email');
            $table->string('whatsapp');
            $table->string('asal_sekolah')->nullable();
            $table->string('kota')->nullable();
            $table->boolean('izin_dihubungi')->default(true);
            $table->unsignedTinyInteger('usia')->nullable();
            $table->text('minat_lain')->nullable();
            // 15 answers (1-5 each)
            $table->unsignedTinyInteger('q1')->default(3);
            $table->unsignedTinyInteger('q2')->default(3);
            $table->unsignedTinyInteger('q3')->default(3);
            $table->unsignedTinyInteger('q4')->default(3);
            $table->unsignedTinyInteger('q5')->default(3);
            $table->unsignedTinyInteger('q6')->default(3);
            $table->unsignedTinyInteger('q7')->default(3);
            $table->unsignedTinyInteger('q8')->default(3);
            $table->unsignedTinyInteger('q9')->default(3);
            $table->unsignedTinyInteger('q10')->default(3);
            $table->unsignedTinyInteger('q11')->default(3);
            $table->unsignedTinyInteger('q12')->default(3);
            $table->unsignedTinyInteger('q13')->default(3);
            $table->unsignedTinyInteger('q14')->default(3);
            $table->unsignedTinyInteger('q15')->default(3);
            // Scores
            $table->decimal('skor_sainsdata', 5, 2)->nullable();
            $table->decimal('skor_ai_robotika', 5, 2)->nullable();
            $table->decimal('skor_keamanan', 5, 2)->nullable();
            $table->string('rekomendasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
