<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TProduct extends Model
{
    use HasFactory;

    protected $table = 't_products';

    protected $fillable = [
        'product_id',
        'transaction_type',
        'quantity',
        'amount',
        'description'
    ];
}
