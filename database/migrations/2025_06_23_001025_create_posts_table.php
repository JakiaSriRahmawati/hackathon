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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->text('caption');
            $table->foreignId('todo_id')->nullable()->constrained('todos');
            $table->foreignId('goal_id')->nullable()->constrained('goals');
            $table->text('image_url')->nullable();
            $table->enum('visibility', ['public', 'friends', 'private'])->default('public');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
