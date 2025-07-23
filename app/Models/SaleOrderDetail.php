<?php
// app/Models/SaleOrderDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'sale_order_detail';
    
    // Since this table has a composite primary key (sales_id, product_id),
    // we tell Laravel that there's no single auto-incrementing key.
    public $incrementing = false;
    protected $primaryKey = null; // No single primary key

    protected $fillable = [
        'sales_id',
        'product_id',
        'quantity',
        'price_at_sale',
        'discount_amount',
    ];

    protected $casts = [
        'price_at_sale' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    /**
     * Get the product associated with the sale detail.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Get the main sale order record.
     */
    public function saleOrder()
    {
        return $this->belongsTo(SaleOrder::class, 'sales_id', 'sales_id');
    }
}
