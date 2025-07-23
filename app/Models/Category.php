<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Laravel assumes a plural table name ('categories'), so this is good practice to define.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'category_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_name',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     * This ensures 'is_active' is treated as a boolean (true/false).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the products for the category.
     * This defines a one-to-many relationship.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
