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
        Schema::create('config_databases', function (Blueprint $table) {
            $table->id();
            $table->string('db_connection');
            $table->string('host');
            $table->string('port');
            $table->string('database');
            $table->string('username');
            $table->string('password');
            $table->string('sid')->nullable();
            $table->string('charset')->nullable();
            $table->string('collation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_databases');
    }
};
