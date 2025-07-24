<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'sales_order_detail'; // Make sure this matches your table name

    /**
     * A sale detail line belongs to one product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
