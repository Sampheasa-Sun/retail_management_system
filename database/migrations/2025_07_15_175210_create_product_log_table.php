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
        Schema::create('product_log', function (Blueprint $table) {
            $table->id('log_id');
            
            // Foreign key relationship to the products table
            $table->foreignId('product_id')->constrained('products', 'product_id');

            $table->string('action_type');
            $table->text('details');
            
            // Use a specific column for the log date instead of timestamps
            $table->timestamp('log_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_log');
    }
};
