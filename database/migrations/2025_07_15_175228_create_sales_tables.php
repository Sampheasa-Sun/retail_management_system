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
        // Main sales order table
        Schema::create('sales_order', function (Blueprint $table) {
            $table->id('sales_id');
            $table->foreignId('employee_id')->constrained('employees', 'employee_id');
            $table->timestamp('sale_date')->useCurrent();
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });

        // Sale order detail table (line items)
        Schema::create('sale_order_detail', function (Blueprint $table) {
            $table->foreignId('sales_id')->constrained('sales_order', 'sales_id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->integer('quantity');
            $table->decimal('price_at_sale', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);

            // Define a composite primary key
            $table->primary(['sales_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_order_detail');
        Schema::dropIfExists('sales_order');
    }
};
