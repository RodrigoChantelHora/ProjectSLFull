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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_description');
            $table->boolean('status')->default(true);
            $table->string('report_token')->unique();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('contact_groups');
            $table->text('message')->nullable();

            // Tipo e intervalo de repetição
            $table->string('scheduleType')->nullable();
            $table->string('selectedTime')->nullable();
            $table->string('selectedWeekday')->nullable();
            $table->string('selectedDay')->nullable();
            $table->string('selectedMonth')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
