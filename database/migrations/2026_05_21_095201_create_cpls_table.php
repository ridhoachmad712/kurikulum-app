<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cpls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_studi_id')->constrained('program_studis')->cascadeOnDelete();
            $table->string('kode', 20);
            $table->text('deskripsi');
            $table->string('keterangan')->nullable();
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->timestamps();
            $table->unique(['program_studi_id','kode']);
        });
    }
    public function down(): void { Schema::dropIfExists('cpls'); }
};
