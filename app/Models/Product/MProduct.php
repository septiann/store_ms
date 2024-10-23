<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MProduct extends Model
{
    use HasFactory;

    protected $table = 'm_products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'initial_stock',
        'category_id',
        'supplier_id',
        'barcode'
    ];
}
