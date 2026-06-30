<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Attendance::where('user_id', $user->id);

        if ($request->filled('month')) {
            $date = Carbon::parse($request->month);
            $query->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year);
        }

        $attendances = $query->orderBy('tanggal', 'desc')->paginate(20);

        return view('employee.history.index', compact('attendances'));
    }
}
