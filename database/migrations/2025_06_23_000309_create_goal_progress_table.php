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
        Schema::create('goal_progress', function (Blueprint $table) { 
            $table->id(); 
            $table->foreignId('goal_id')->constrained()->onDelete('cascade'); 
            $table->date('date'); 
            $table->boolean('is_done')->default(false); 
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_progress');
    }
};
