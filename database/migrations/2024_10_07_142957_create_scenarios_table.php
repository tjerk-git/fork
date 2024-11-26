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
        Schema::create('scenarios', function (Blueprint $table) {
            $table->id();
             $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->string('body')->nullable();
            $table->string('attachment')->nullable();
            $table->string('slug');
            $table->string('access_code')->nullable();
            $table->boolean('is_public')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenarios');
    }
};
