<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'service_id',
        'price',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
