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
            $table->foreignId('assignee_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_done')->default(false);
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
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
