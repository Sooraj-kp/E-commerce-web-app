<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE payments ENGINE=InnoDB');
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('payment_gateway_config_id')->nullable()->after('payment_method_id')->constrained('payment_gateway_configs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['payment_gateway_config_id']);
            $table->dropColumn(['payment_gateway_config_id']);
        });
    }
};
