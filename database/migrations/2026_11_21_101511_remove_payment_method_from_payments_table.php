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
            $table->dropColumn('payment_method');

        });

        Schema::table('payments',function(Blueprint $table){
            $table->unsignedBigInteger('payment_method_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
        });

        DB::statement("
            UPDATE payments p
            JOIN payment_methods pm ON p.payment_method_id = pm.id
            SET p.payment_method = pm.code
        ");
    }
};
