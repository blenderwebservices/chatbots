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
        Schema::table('llm_models', function (Blueprint $table) {
            $table->string('last_check_status')->nullable()->after('active');
            $table->timestamp('last_check_at')->nullable()->after('last_check_status');
            $table->json('configuration')->nullable()->after('last_check_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('llm_models', function (Blueprint $table) {
            $table->dropColumn(['last_check_status', 'last_check_at', 'configuration']);
        });
    }
};
