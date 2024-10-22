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
        if (!Schema::hasTable('t_suppliers')) {
            Schema::create('t_suppliers', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('supplier_id');
                $table->decimal('total_amount',10,2);
                $table->timestamp('transaction_date')->useCurrent();
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('supplier_id')->references('id')->on('m_suppliers')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_suppliers');
    }
};
