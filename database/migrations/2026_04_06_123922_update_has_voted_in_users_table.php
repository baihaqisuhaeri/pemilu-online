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
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('has_voted');
        $table->boolean('voted_presiden')->default(false)->after('role');
        $table->boolean('voted_dpr')->default(false)->after('voted_presiden');
        $table->boolean('voted_dpd')->default(false)->after('voted_dpr');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['voted_presiden', 'voted_dpr', 'voted_dpd']);
        $table->boolean('has_voted')->default(false);
    });
    }
};
