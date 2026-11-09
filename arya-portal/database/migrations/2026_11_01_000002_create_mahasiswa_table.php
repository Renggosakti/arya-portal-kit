<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->index(); // sering dicari, jadi diberi indeks
            $table->string('nrp', 10)->unique(); // NRP ITS 10 digit, disimpan sebagai string
            $table->string('email')->unique();
            $table->foreignId('departemen_id')->constrained('departemen')->cascadeOnDelete();
            $table->boolean('is_active')->default(true); // false = cuti akademik
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
