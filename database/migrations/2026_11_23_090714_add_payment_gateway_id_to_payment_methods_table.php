<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE payment_methods ENGINE=InnoDB');

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->foreignId('payment_gateway_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('payment_gateways')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropForeign(['payment_gateway_id']);
            $table->dropColumn('payment_gateway_id');
        });
    }
};