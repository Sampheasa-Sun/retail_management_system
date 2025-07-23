<?php
// app/Models/SaleOrder.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;

    protected $table = 'sales_order';
    protected $primaryKey = 'sales_id';

    protected $fillable = [
        'employee_id',
        'sale_date',
        'total_amount',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the employee who made the sale.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the details (line items) for the sale order.
     */
    public function details()
    {
        return $this->hasMany(SaleOrderDetail::class, 'sales_id', 'sales_id');
    }
}

