<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'barber_id',
        'total_price',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
