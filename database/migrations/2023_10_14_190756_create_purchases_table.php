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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->nullable()->references('id')->on('providers')->comment('ID del proveedor a quien se le compra');
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->comment('ID del producto en inventario en caso de existir');
            $table->decimal('amount_usd', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->decimal('dolar_price', 10, 2);
            $table->integer('quantity_meters');
            $table->date('date');
            $table->longText('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
