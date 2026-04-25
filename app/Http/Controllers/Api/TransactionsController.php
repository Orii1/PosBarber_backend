<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'total_price'   => 'required|numeric|min:0',
            'paid_amount'   => 'required|numeric|min:0',
            'payment_type'  => 'required|in:cash,qris',
        ]);

        if ($validated['paid_amount'] < $validated['total_price']) {
            return response()->json(['message' => 'Jumlah bayar kurang dari total'], 422);
        }

        $change = $validated['paid_amount'] - $validated['total_price'];

        $transaction = Transaction::create([
            'customer_id'    => $validated['customer_id'],
            'total_price'    => $validated['total_price'],
            'paid_amount'    => $validated['paid_amount'],
            'change_amount'  => $change,
            'payment_type'   => $validated['payment_type'],
            'user_id'        => Auth::user()->id,
        ]);

        return response()->json([
            'message' => 'Transaksi berhasil dibuat',
            'transaction' => $transaction
        ]);
    }

    public function index()
    {
        $transactions = Transaction::with('customer.user', 'user')->latest()->get();

        return response()->json($transactions);
    }

    public function report(Request $request)
    {
        $request->validate([
            'date'  => 'nullable|date',        // format: 2025-06-22
            'month' => 'nullable|date_format:Y-m', // format: 2025-06
        ]);

        $query = Transaction::with('customer.user', 'user');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', Carbon::parse($request->month)->month)
                ->whereYear('created_at', Carbon::parse($request->month)->year);
        }

        $transactions = $query->latest()->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_income'       => $transactions->sum('total_price'),
        ];

        return response()->json([
            'summary'      => $summary,
            'transactions' => $transactions
        ]);
    }
}
