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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('category_id');
                $table->unsignedInteger('unit_id');

                $table->string('name');
                $table->string('slug');
                $table->string('code')->unique()->nullable();
                $table->integer('quantity');
                $table->decimal('buying_price',15,2);

                $table->decimal('selling_price',15,2);
                $table->integer('stock');
                $table->integer('tax')->nullable();
                $table->tinyInteger('tax_type')->nullable();
                $table->text('notes')->nullable();

                $table->string('image')->nullable();

                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
