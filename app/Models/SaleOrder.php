<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;

    protected $table = 'sales_order'; // Make sure this matches your table name

    protected $fillable = [
        'user_id',
        'total',
    ];

    /**
     * Get the user (employee) who made the sale.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * An order has many detail lines.
     */
    public function details()
    {
        return $this->hasMany(SaleOrderDetail::class, 'sales_order_id');
    }
}
