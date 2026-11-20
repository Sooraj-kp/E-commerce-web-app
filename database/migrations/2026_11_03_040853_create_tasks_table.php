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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            // Assign the task to a staff user
            $table->unsignedBigInteger('assigned_to');

            // Foreign key connection: tasks.assigned_to -> users.id
            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->dateTime('due_at')->nullable();
            $table->unsignedInteger('estimated_minutes')->nullable();

            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->enum('status', [
                'pending',
                'in_progress',
                'submitted',
                'needs_revision',
                'completed'
            ])->default('pending');

            $table->text('admin_feedback')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};