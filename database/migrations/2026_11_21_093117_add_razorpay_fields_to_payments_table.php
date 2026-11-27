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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('gateway_order_id')->nullable()->unique();
            $table->string('currency',3)->default('INR');
            $table->timestamp('paid_at')->nullable();
            $table->text('failure_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['gateway_order_id']);
            $table->dropColumn([
                'gateway_order_id',
                'currency',
                'paid_at',
                'failure_reason',

            ]);
        });
    }
};
