<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('payment_methods', 'payments');
    }

    public function down(): void
    {
        Schema::rename('payments', 'payment_methods');
    }
};