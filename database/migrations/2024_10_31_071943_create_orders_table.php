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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('customer_id');

                $table->timestamp('order_date');
                $table->tinyInteger('status')->comment('0-Pending, 1-Complete');
                $table->integer('total_products');
                $table->decimal('sub_total',15,2);
                $table->decimal('vat',15,2);

                $table->decimal('total',15,2);
                $table->string('invoice_no');
                $table->string('payment_type');
                $table->decimal('pay',15,2);
                $table->decimal('due',15,2);
                $table->timestamps();

                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
