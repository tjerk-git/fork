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
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->nullable();
            $table->text('condition')->nullable();
            $table->text('description')->nullable();
            $table->integer('fork_to_step')->nullable();
            $table->text('attachment')->nullable();
            $table->text('open_question')->nullable();
            $table->text('multiple_choice_question')->nullable();
            $table->text('multiple_choice_option_1')->nullable();
            $table->text('multiple_choice_option_2')->nullable();
            $table->text('multiple_choice_option_3')->nullable();
            $table->foreignId('scenario_id')->constrained('scenarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
