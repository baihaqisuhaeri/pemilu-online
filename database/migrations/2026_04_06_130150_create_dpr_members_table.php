<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dpr_members', function (Blueprint $table) {
        $table->id();
        $table->foreignId('dpr_candidate_id')->constrained('dpr_candidates')->onDelete('cascade');
        $table->integer('nomor_urut');
        $table->string('nama');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dpr_members');
    }
};
