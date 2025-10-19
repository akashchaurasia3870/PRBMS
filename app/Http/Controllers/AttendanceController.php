<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // index
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $search = $request->search;
        if (empty($search)) {
            $search = null;
        }

        $query = DB::table('attendance_view')
            ->selectRaw('id, name, month, total_days as totalWorkingDays, present_count as presentDays, (total_days - present_count) as absentDays')
            ->where('month', $month)
            ->where('year', $year);

        if ($search !== null) {
            $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id')->paginate(10)->appends($request->all());

        return view('modules.attendance.view-users-attendance', ['users' => $users]);
    }

    public function get_user_list()
    {
        $users = User::where('deleted', 0)->paginate(10);
        // dd($users);
        return view('modules.attendance.mark-users-attendance',['users'=>$users]);
    }

    public function get_user_attendance_details(Request $request)
    {
        // show 
        $id = $request->id;
        $search = $request->search;
        if (empty($search)) {
            $search = null;
        }

        $query = DB::table('attendance_view')
            ->selectRaw('id, name, month,year, total_days as totalWorkingDays, present_count as presentDays, (total_days - present_count) as absentDays')
            ->where('id',$id);

        $data = $query->orderBy('id')->paginate(10)->appends($request->all());

        return view('modules.attendance.view-user-attendance-details',['users'=>$data]);
    }

    public function mark_attendance(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'date' => 'required|date',
            'status' => 'required|string|in:present,absent',
        ]);

        $attendance = Attendance::where('user_id', $validated['user_id'])
            ->where('date', $validated['date'])
            ->first();

        if ($attendance) {
            $attendance->status = $validated['status'];
            $attendance->save();
            $message = 'Attendance updated successfully.';
        } else {
            $attendance = Attendance::create($validated);
            $message = 'Attendance marked successfully.';
        }

        $users = User::where('deleted', 0)->paginate(10);
        // dd($users);
        return view('modules.attendance.mark-users-attendance',['users'=>$users,'message'=>$message]);
    }
    public function mark_all_attendance(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|string|in:present,absent',
        ]);

        $users = User::where('deleted', 0)->get();
        foreach ($users as $user) {
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', $validated['date'])
                ->first();

            if ($attendance) {
                $attendance->status = $validated['status'];
                $attendance->save();
            } else {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $validated['date'],
                    'status' => $validated['status'],
                ]);
            }
        }

        $usersPaginated = User::where('deleted', 0)->paginate(10);
        $message = 'Attendance marked/updated for all users successfully.';
        return view('modules.attendance.mark-users-attendance', [
            'users' => $usersPaginated,
            'message' => $message
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'date' => 'required|date',
            'status' => 'required|string',
        ]);

        $attendance = \App\Models\Attendance::create($validated);

        return response()->json($attendance, 201);
    }

    public function user_attendance_details(Request $request)
    {
        $id = $request->id;
        $data = \App\Models\Attendance::findOrFail($id);
        return view('modules.attendance.edit-user-attendance',['data'=>$data]);        
    }

    public function update_user_attendance_details(Request $request)
    {
        $id = $request->id;
        $data = \App\Models\Attendance::findOrFail($id);
        return view('modules.attendance.edit-user-attendance',['data'=>$data]);        
    }

    // public function update(Request $request)
    // {
    //     $attendance = \App\Models\Attendance::findOrFail($id);

    //     $validated = $request->validate([
    //         'user_id' => 'sometimes|integer',
    //         'date' => 'sometimes|date',
    //         'status' => 'sometimes|string',
    //     ]);

    //     $attendance->update($validated);

    //     return response()->json($attendance);
    // }

    // public function destroy(Request $request)
    // {
    //     $attendance = \App\Models\Attendance::findOrFail($request->id);
    //     $attendance->delete();

    //     return response()->json(['message' => 'Attendance deleted successfully.']);
    // }
}
