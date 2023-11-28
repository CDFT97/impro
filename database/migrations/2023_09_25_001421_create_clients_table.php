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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name',150);
            $table->string('last_name',150);
            $table->string('ci',15)->unique();
            $table->string('address',150);
            $table->string('phone_number',50);
            $table->string('email',80)->unique();
            $table->string('company',80);
            $table->tinyInteger('type')->comment("0 - Normal, 1 - Publicista");
            $table->integer('discount')->nullable()->comment("% de descuento");
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};