<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'type',
        'bank_name',
        'account_holder',
        'account_number',
        'photo'
    ];
}
