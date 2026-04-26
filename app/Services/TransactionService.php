<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            // hitung total
            $total = 0;
            $services = Service::whereIn('id', collect($data['services'])->pluck('id'))->get();

            foreach ($services as $service) {
                $total += $service->price;
            }

            // buat transaksi utama
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . time(),
                'user_id' => $data['user_id'],
                'barber_id' => $data['barber_id'],
                'total_price' => $total,
            ]);

            // simpan detail
            foreach ($services as $service) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'service_id' => $service->id,
                    'price' => $service->price,
                ]);
            }

            return $transaction->load('details.service');;
        });
    }
}
