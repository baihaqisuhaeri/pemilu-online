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
        Schema::table('candidates', function (Blueprint $table) {
        $table->foreignId('election_type_id')->default(1)->after('id')->constrained('election_types');
    });

    Schema::table('votes', function (Blueprint $table) {
        $table->foreignId('election_type_id')->default(1)->after('candidate_id')->constrained('election_types');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
        $table->dropForeign(['election_type_id']);
        $table->dropColumn('election_type_id');
    });

    Schema::table('votes', function (Blueprint $table) {
        $table->dropForeign(['election_type_id']);
        $table->dropColumn('election_type_id');
    });
    }
};
