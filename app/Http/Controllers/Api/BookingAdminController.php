<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Validation\Rule;

class BookingAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('pending'); // optional query: ?status=pending

        $bookings = Booking::with(['customer.user', 'service', 'barber'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('booking_time')
            ->get();

        return response()->json($bookings);
    }

    public function show($id)
    {
        $booking = Booking::with(['customer.user', 'service', 'barber'])->find($id);

        if (! $booking) {
            return response()->json(['message' => 'Booking tidak ditemukan'], 404);
        }

        return response()->json($booking);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'done', 'cancelled'])],
        ]);

        $booking = Booking::find($id);

        if (! $booking) {
            return response()->json(['message' => 'Booking tidak ditemukan'], 404);
        }

        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'message' => 'Status booking diperbarui',
            'booking' => $booking
        ]);
    }
}
