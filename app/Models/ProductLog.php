<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    use HasFactory;

    protected $table = 'product_log';
    protected $primaryKey = 'log_id';

    // Tell Laravel to use 'log_date' instead of the default 'created_at' and 'updated_at'
    const CREATED_AT = 'log_date';
    const UPDATED_AT = null; // No 'updated_at' column in this table

    protected $fillable = [
        'product_id',
        'action_type',
        'details',
    ];

    protected $casts = [
        'log_date' => 'datetime',
    ];

    /**
     * Get the product that the log entry belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
