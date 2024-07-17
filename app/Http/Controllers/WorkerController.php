<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WorkerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role != 'worker') {
            return redirect()->intended('worker/login');
        }
        $assignments =  $user->myAssignments;

        $assignmentsToday = $user->myAssignments->where('date', Carbon::now()->format('Y-m-d'));
        AssignmentResource::collection($assignments);

        return Inertia::render('client/WorkerPage', [
            'workingProgress' => [
                'today' =>  $assignments->where('date', Carbon::now()->format('Y-m-d'))->count(),
                'ended' => $assignments->where('status', 'completed')->count(),
                'in_progress' => $assignments->whereIn('status', ['assignment', 'pending'])->where('date', Carbon::now()->format('Y-m-d'))->count(),
            ],
            'assignmentsToday' => AssignmentResource::collection($assignmentsToday),
            'assignments' => AssignmentResource::collection($assignments),
        ]);
    }

    public function updateStatus(Assignment $assignment, Request $request)
    {
        $assignment->update([
            'status' => $request->status
        ]);

        if ($assignment->status == 'completed') {
            $assignment->update([
                'end_time' => Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i')
            ]);
            $assignment->reservation->update([
                'status' =>  'finished'
            ]);
        } else if ($assignment->status == 'rejected') {
            $assignment->reservation->update([
                'status' =>  'rejected'
            ]);
        }
        return redirect()->intended('/worker')->with('toast', "Status Tugas Berhasil Diubah");
    }
}
