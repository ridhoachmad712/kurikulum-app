<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cpmk_cpls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpmk_id')->constrained('cpmks')->cascadeOnDelete();
            $table->foreignId('cpl_id')->constrained('cpls')->cascadeOnDelete();
            $table->boolean('has_relation')->default(false);
            $table->decimal('bobot_persen', 6, 2)->default(0);
            $table->timestamps();
            $table->unique(['cpmk_id','cpl_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('cpmk_cpls'); }
};
