<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    use HasFactory;

    protected $table = 'product_log';

    /**
     * Get the user (employee) who made the change.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the product that was changed.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
