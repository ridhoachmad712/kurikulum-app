<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable();           // snapshot nama pelaku
            $table->string('action', 20);                      // created | updated | deleted
            $table->string('subject_type')->nullable();        // class basename, mis. "Cpl"
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_label')->nullable();       // label terbaca, mis. "CPL-1"
            $table->text('description')->nullable();
            $table->json('changes')->nullable();               // atribut yang berubah
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['subject_type', 'subject_id']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
