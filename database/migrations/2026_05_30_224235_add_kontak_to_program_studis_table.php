<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->string('alamat')->nullable()->after('tujuan');
            $table->string('email')->nullable()->after('alamat');
            $table->string('telepon', 50)->nullable()->after('email');
            $table->string('website')->nullable()->after('telepon');
        });
    }

    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'email', 'telepon', 'website']);
        });
    }
};
