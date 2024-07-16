<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/dashboard/Index', [
            'title' => 'Dashboard',
            'customer' => User::where('role', 'user')->count(),
            'reservation' => Reservation::count(),
            'reservationActive' => Reservation::where('status' ,'!=', ['finished', 'rejected'])->count(),
            'reservationFinished' => Reservation::where('status', 'finished')->count()
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
