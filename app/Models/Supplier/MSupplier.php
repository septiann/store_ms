<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSupplier extends Model
{
    use HasFactory;

    protected $table = 'm_suppliers';

    protected $fillable = [
        'name',
        'contact_name',
        'phone_number',
        'address'
    ];
}
