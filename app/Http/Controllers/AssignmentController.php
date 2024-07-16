<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use PhpParser\Node\Expr\Assign;

class AssignmentController extends Controller
{
    public function assignJob(Reservation $reservation, Request $request)
    {
        $data = $request->validate([
            'workerId' => [
                'required', Rule::exists(User::class, 'id')
            ],
        ]);

        $reservation->update([
            'status' => 'waiting'
        ]);

        Assignment::create([
            'reservation_id' => $reservation->id,
            'worker_id' => $data['workerId'],
            'status' => 'assignment',
            'date' => Carbon::now()->setTimezone('Asia/Jakarta')->toDateString(),
            'start_time' => Carbon::now()->setTimezone('Asia/Jakarta')->toTimeString(),
        ]);

        return redirect()->intended('/admin/reservations')->with('toast', "Job Berhasil Diberikan");
    }

    public function updateAssignJob(Assignment $assignment, Request $request)
    {
        $data = $request->validate([
            'workerId' => [
                'required', Rule::exists(User::class, 'id')
            ],
        ]);

        $assignment->update([
            'worker_id' => $data['workerId'],
        ]);

        return redirect()->intended('/admin/schedules')->with('toast', "Mechanic Berhasil Diganti");
    }


    public function updateStatus(Assignment $assignment, Request $request)
    {
        $data = $request->validate([
            'status' => [
                'required', Rule::in(['assignment', 'pending', 'completed', 'cancelled'])
            ],
        ]);

        $assignment->update([
            'status' => $data['status'],
        ]);

        return redirect()->intended('/admin/reservations')->with('toast', "Status Job Berhasil Diubah");
    }

    public function completeJob(Assignment $assignment)
    {
        $assignment->update([
            'status' => 'completed',
            'end_time' => Carbon::now()->toTimeString(),
        ]);
    }

    public function delete(Assignment $assignment)
    {
        if ($assignment->reservation->status == 'waiting') {
            $assignment->reservation->update([
                'status' => 'confirmed'
            ]);
        }

        $assignment->delete();

        return redirect()->intended('/admin/schedules')->with('toast', "Job Berhasil Dihapus");
    }

    public function index(Request $request)
    {
        $date =  Carbon::now()->toDateString();

        $workerAvailable = User::where('role', 'worker')->whereDoesntHave('assignments', function ($query) use ($date) {
            $query->where('status', 'assignment');
            $query->where('date', $date);
        })->get();

        return Inertia::render('admin/schedule/Index', [
            'title' => 'Schedules',
            'assignments' => AssignmentResource::collection(Assignment::latest()->paginate(10)),
            'workerAvailable' => $workerAvailable
        ]);
    }
}
