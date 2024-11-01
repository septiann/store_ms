<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'unit_id',
        'name',
        'slug',
        'code',
        'quantity',
        'buying_price',
        'selling_price',
        'stock',
        'tax',
        'tax_type',
        'notes',
        'image'
    ];

    /**
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the unit that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo {
        return $this->belongsTo(Unit::class);
    }
}
