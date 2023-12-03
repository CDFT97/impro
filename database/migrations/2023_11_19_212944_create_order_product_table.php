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
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products', 'id')->onDelete('cascade');
            $table->decimal("dollar_price", 8, 2);
            $table->decimal("unit_price_usd", 8, 2);
            $table->decimal("unit_price_bs", 8, 2);
            $table->decimal("format", 8, 2);
            $table->integer("quantity");
            $table->decimal("m", 8, 2);
            $table->decimal("m2", 8, 2);
            $table->decimal("total_price_usd", 8, 2);
            $table->decimal("total_price_bs", 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
