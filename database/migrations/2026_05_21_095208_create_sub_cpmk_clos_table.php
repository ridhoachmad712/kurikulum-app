<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sub_cpmk_clos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_cpmk_id')->constrained('sub_cpmks')->cascadeOnDelete();
            $table->foreignId('clo_id')->constrained('clo_taxonomis')->cascadeOnDelete();
            $table->boolean('is_tertinggi')->default(false);
            $table->timestamps();
            $table->unique(['sub_cpmk_id','clo_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('sub_cpmk_clos'); }
};
