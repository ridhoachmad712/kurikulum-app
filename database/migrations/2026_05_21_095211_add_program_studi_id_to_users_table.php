<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('program_studi_id')->nullable()->constrained('program_studis')->nullOnDelete()->after('email');
            $table->string('nip', 30)->nullable()->after('program_studi_id');
            $table->string('jabatan')->nullable()->after('nip');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['program_studi_id']);
            $table->dropColumn(['program_studi_id','nip','jabatan']);
        });
    }
};
