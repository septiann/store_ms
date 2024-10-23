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
        if (!Schema::hasTable('m_sales')) {
            Schema::create('m_sales', function (Blueprint $table) {
                $table->id();
                $table->timestamp('transaction_date')->useCurrent();
                $table->unsignedInteger('employee_id');
                $table->unsignedInteger('customer_id');
                $table->decimal('total_amount', 10, 2);
                $table->string('payment_method',50);
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_sales');
    }
};
