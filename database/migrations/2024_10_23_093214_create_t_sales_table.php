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
        if (!Schema::hasTable('t_sales')) {
            Schema::create('t_sales', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('sale_id');
                $table->unsignedInteger('product_id');
                $table->integer('quantity');
                $table->decimal('price',10,2);
                $table->timestamps();

                $table->foreign('sale_id')->references('id')->on('m_sales')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('m_products')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_sales');
    }
};
