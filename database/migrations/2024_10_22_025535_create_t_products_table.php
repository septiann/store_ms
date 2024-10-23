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
        if (!Schema::hasTable('t_products')) {
            Schema::create('t_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('product_id');
                $table->enum('transaction_type', ['IN', 'OUT'])->nullable(); // IN untuk stok masuk dan OUT untuk stok keluar
                $table->integer('quantity'); // Jumlah barang yang masuk atau keluar
                $table->decimal('amount',10,2)->nullable(); // Harga saat transaksi
                $table->text('description')->nullable(); // ex: Restock atau Penjualan
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('m_products')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_products');
    }
};
