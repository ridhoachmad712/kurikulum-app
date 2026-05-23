<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mk_cpls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->cascadeOnDelete();
            $table->foreignId('cpl_id')->constrained('cpls')->cascadeOnDelete();
            $table->enum('level', ['Tinggi','Sedang','Rendah'])->nullable();
            $table->timestamps();
            $table->unique(['mata_kuliah_id','cpl_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('mk_cpls'); }
};
