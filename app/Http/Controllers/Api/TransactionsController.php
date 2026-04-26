<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\TransactionService;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;

class TransactionsController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = $this->transactionService->create($request->validated());

        return response()->json([
            'message' => 'Transaksi berhasil',
            'data' => new TransactionResource($transaction)
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
