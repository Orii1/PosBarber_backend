<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'customers' => Customer::count(),
            'transactions' => Transaction::count(),
            'services' => Service::count(),
            'barbers' => User::where('role', 'barber')->count(),
        ]);
    }

    public function recentBookings()
    {
        $bookings = Booking::with(['customer.user', 'service','barber'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'customer_name' => $booking->customer->user->name,
                    'service_name' => $booking->service->name,
                    'barber_name' => $booking->barber->user->name,
                    'booking_time' => $booking->booking_time,
                    'status' => $booking->status,
                ];
            });

        return response()->json($bookings);
    }
}
