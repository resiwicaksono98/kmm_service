<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Report;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\ReportResource;
use App\Http\Resources\ReservationResource;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function createPage()
    {
        return Inertia::render('admin/report/Create', [
            'title' => 'Create Report',
            'back' => '/admin/reports',
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date'
        ]);

        $pathFile = 'payment-reports/' . Str::slug($data['name']) . '-' . Carbon::now()->timestamp . '.xlsx';
        Excel::store(new ReportExport($data), $pathFile, 'public');
        $data['file'] =  $pathFile;

        Report::create([
            'name' => $data['name'],
            'start_date' => $data['startDate'],
            'end_date' => $data['endDate'],
            'file_url' => $data['file']
        ]);


        return redirect()->intended('/admin/reports')->with('toast', "Report Berhasil Dibuat");
    }

    public function index(Request $request)
    {
        $reports = Report::latest()->paginate(10);
        return Inertia::render('admin/report/Index', [
            'title' => 'Laporan',
            'reports' => ReportResource::collection($reports),
        ]);
    }

    public function delete(Report $report)
    {
        $report->delete();

        return redirect()->intended('/admin/reports')->with('toast', "Report Berhasil Dihapus");
    }
}
