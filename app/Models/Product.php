<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Note: Your table name is singular. Laravel convention is plural ('products').
    // We must specify the table name here to match your schema.
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_name',
        'category_id',
        'cost_price',
        'selling_price',
        'quantity_in_stock',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    /**
     * Get the category that the product belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    /**
     * Get the product logs for the product.
     */
    public function logs()
    {
        return $this->hasMany(ProductLog::class, 'product_id', 'product_id');
    }

    /**
     * Get the sale order details for the product.
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleOrderDetail::class, 'product_id', 'product_id');
    }
}
