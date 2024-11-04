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
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();

                $table->string('name');
                $table->string('email')->unique()->nullable();
                $table->string('phone')->unique()->nullable();
                $table->text('address')->nullable();
                $table->string('shop_name')->nullable();

                $table->string('type')->nullable();
                $table->string('photo')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('account_holder')->nullable();
                $table->string('account_number')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};