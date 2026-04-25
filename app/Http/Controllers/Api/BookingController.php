<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id'   => 'required|exists:services,id',
            'barber_id'   => 'required|exists:barbers,id',
            'booking_date'  => 'required|date',
            'booking_time'  => 'required|date_format:H:i',
            'notes'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bookingDateTime = $request->booking_date . ' ' . $request->booking_time . ':00';

        if (now()->gt($bookingDateTime)) {
            return response()->json(['message' => 'Waktu booking harus di masa depan'], 422);
        }

        $customer = Customer::where('user_id', $request->user()->id)->first();

        if (! $customer) {
            return response()->json(['message' => 'Data customer tidak ditemukan'], 404);
        }

        $booking = Booking::create([
            'customer_id'  => $customer->id,
            'service_id'   => $request->service_id,
            'barber_id'   => $request->barber_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'notes'        => $request->notes,
            'status'       => 'pending',
        ]);

        return response()->json([
            'message' => 'Booking berhasil dibuat',
            'booking' => $booking
        ], 201);
    }

    public function index(Request $request)
    {
        $customer = Customer::where('user_id', $request->user()->id)->first();

        if (! $customer) {
            return response()->json(['message' => 'Customer tidak ditemukan'], 404);
        }

        $bookings = Booking::with('service')
            ->where('customer_id', $customer->id)
            ->orderBy('booking_time', 'desc')
            ->get();

        return response()->json($bookings);
    }
}
