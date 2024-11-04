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
        if (!Schema::hasTable('purchase_details')) {
            Schema::create('purchase_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('purchase_id');
                $table->unsignedInteger('product_id');
                $table->integer('quantity');
                $table->decimal('unit_cost',15,2);
                $table->decimal('total',15,2);

                $table->timestamps();

                $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
