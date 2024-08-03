<?php

namespace App\Exports;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ReportExport implements FromView
{
    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function view(): View
    {
        $reservations = Reservation::query();

        $reservations->where("status", 'finished');

        if (!empty($this->data['startDate'])) {
            $reservations->whereDate('date', '>=', $this->data['startDate']);
        }

        if (!empty($this->data['endDate'])) {
            $reservations->whereDate('date', '<=', $this->data['endDate']);
        }

        $reservations = $reservations->get();
        $formatReservations =  $reservations->map(function ($item) {
            return $item;
        });

        return view('report', [
            'reservations' => $formatReservations,
        ]);
    }
}
