<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/dashboard/Index', [
            'title' => 'Dashboard',
        ]);
    }

    public function clientIndex()
    {
        $reservations = Reservation::where('user_id', auth()->user()->id)->latest()->get();
        return Inertia::render('client/Dashboard', [
            'reservations' => ReservationResource::collection($reservations),
        ]);
    }

    public function detailOrder(string $uniqueNumber)
    {
        $reservation = Reservation::where('unique_number', $uniqueNumber)->first();
        if (!$reservation) {
            return redirect()->intended('/dashboard')->with('toast', 'Reservasi tidak ditemukan');
        }
        return Inertia::render('client/DetailOrder', [
            'reservation' => new ReservationResource($reservation),
        ]);
    }

    public function cancelOrder(string $uniqueNumber)
    {
        $reservation = Reservation::where('unique_number', $uniqueNumber)->first();
        $reservation->update(['status' => 'rejected']);
        return redirect()->intended('/dashboard')->with('toast', 'Reservasi berhasil dibatalkan');
    }
}
