<?php

namespace App\Http\Controllers;

use App\Http\Resources\PackageResource;
use App\Http\Resources\ReservationResource;
use App\Models\Package;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function indexPage(Request $request)
    {
        $user = auth()->user()->id;
        $reservations = Reservation::where('user_id', $user)
            ->whereIn('status', ['pending', 'confirmed', 'waiting'])
            ->get();

        if ($reservations->isNotEmpty()) {
            return redirect()->intended('/dashboard')->with('toast', "Anda memiliki reservasi yang sedang diproses");
        }
        $date = Carbon::parse($request->date)->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $morningCount = Reservation::where('date', $date)
            ->where('session', 'pagi')
            ->whereNotIn('status', ['finished', 'rejected'])
            ->count();
        $afternoonCount = Reservation::where('date', $date)
            ->where('session', 'siang')
            ->whereNotIn('status', ['finished', 'rejected'])
            ->count();
        return Inertia::render('client/Reservation', [
            'packages' => PackageResource::collection(Package::all()),
            'availableSession' => [
                'morning' => $morningCount >= 3 ? false : true,
                'afternoon' => $afternoonCount >= 3 ? false : true,
            ]
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'packageId' => 'required',
            'date' => 'required',
            'session' => 'required',
        ]);

        Reservation::create([
            'user_id' => auth()->user()->id,
            'package_id' => $data['packageId'],
            'unique_number' => substr(md5(uniqid(mt_rand(), true)), 0, 8),
            'date' => $data['date'],
            'session' => $data['session'],
        ]);

        return redirect()->intended('/dashboard')->with('toast', "Reservation Berhasil Dibuat");
    }

    public function editPage(Reservation $reservation, Request $request)
    {
        $date = Carbon::parse($request->date)->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $morningCount = Reservation::where('date', $date)
            ->where('session', 'pagi')
            ->whereNotIn('status', ['finished', 'rejected'])
            ->count();
        $afternoonCount = Reservation::where('date', $date)
            ->where('session', 'siang')
            ->whereNotIn('status', ['finished', 'rejected'])
            ->count();


        return Inertia::render('admin/reservation/Edit', [
            'title' => 'Update Reservation',
            'back' => '/admin/reservations',
            'reservation' => new ReservationResource($reservation),
            'packages' => PackageResource::collection(Package::all()),
            'availableSession' => [
                'morning' => $morningCount >= 3 ? false : true,
                'afternoon' => $afternoonCount >= 3 ? false : true,
            ]
        ]);
    }

    public function edit(Reservation $reservation, Request $request)
    {
        $data = $request->validate([
            'packageId' => 'required',
            'date' => 'required',
            'session' => 'required',
        ]);

        $reservation->update([
            'package_id' => $data['packageId'],
            'unique_number' => substr(md5(uniqid(mt_rand(), true)), 0, 8),
            'date' => $data['date'],
            'session' => $data['session'],
        ]);

        return redirect()->intended('/admin/reservations')->with('toast', "Reservation Berhasil Diubah");
    }

    public function index()
    {
        $reservations = Reservation::latest()->paginate(10);
        return Inertia::render('admin/reservation/Index', [
            'title' => 'Reservation',
            'reservations' => ReservationResource::collection($reservations),
        ]);
    }

    public function delete(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->intended('/admin/reservations')->with('toast', "Reservation Berhasil Dihapus");
    }

    public function quickUpdateStatus(Reservation $reservation, Request $request)
    {
        $data = $request->validate([
            'status' => 'required',
        ]);

        $reservation->update([
            'status' => $data['status'],
        ]);

        return redirect()->intended('/admin/reservations')->with('toast', "Status Reservation Berhasil Diubah");
    }
}
