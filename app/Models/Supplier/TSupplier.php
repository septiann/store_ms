<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TSupplier extends Model
{
    use HasFactory;

    protected $table = 't_suppliers';

    protected $fillable = [
        'supplier_id',
        'total_amount',
        'transaction_date',
        'description'
    ];

    /**
     * Get the MSupplier that owns the TSupplier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(MSupplier::class);
    }
}
