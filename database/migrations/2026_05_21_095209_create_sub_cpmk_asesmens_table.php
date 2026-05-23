<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sub_cpmk_asesmens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_cpmk_id')->constrained('sub_cpmks')->cascadeOnDelete();
            $table->decimal('tugas', 5, 2)->default(0);
            $table->decimal('quis', 5, 2)->default(0);
            $table->decimal('kasus_proyek', 5, 2)->default(0);
            $table->decimal('uts', 5, 2)->default(0);
            $table->decimal('uas', 5, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sub_cpmk_asesmens'); }
};
