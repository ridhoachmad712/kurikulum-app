<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_lulusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_studi_id')->constrained('program_studis')->cascadeOnDelete();
            $table->string('kode', 10);   // PL1, PL2, ...
            $table->string('nama', 255);  // Wirausahawan Pemula
            $table->text('deskripsi')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->unique(['program_studi_id', 'kode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_lulusans');
    }
};
