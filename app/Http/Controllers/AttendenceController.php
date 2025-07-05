<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendence;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AttendenceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $search = $request->search;
        if (empty($search)) {
            $search = null;
        }

        $query = DB::table('attendence_view')
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

        return view('modules.attendence.view-users-attendence', ['users' => $users]);
    }

    public function get_user_list()
    {
        $users = User::where('deleted', 0)->paginate(10);
        // dd($users);
        return view('modules.attendence.mark-users-attendence',['users'=>$users]);
    }

    public function get_user_attendence_details(Request $request)
    {
        $id = $request->id;
        $search = $request->search;
        if (empty($search)) {
            $search = null;
        }

        $query = DB::table('attendence_view')
            ->selectRaw('id, name, month,year, total_days as totalWorkingDays, present_count as presentDays, (total_days - present_count) as absentDays')
            ->where('id',$id);

        $data = $query->orderBy('id')->paginate(10)->appends($request->all());

        return view('modules.attendence.view-user-attendence-details',['users'=>$data]);
    }

    public function mark_attendence(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'date' => 'required|date',
            'status' => 'required|string|in:present,absent',
        ]);

        $attendance = Attendence::where('user_id', $validated['user_id'])
            ->where('date', $validated['date'])
            ->first();

        if ($attendance) {
            $attendance->status = $validated['status'];
            $attendance->save();
            $message = 'Attendance updated successfully.';
        } else {
            $attendance = Attendence::create($validated);
            $message = 'Attendance marked successfully.';
        }

        $users = User::where('deleted', 0)->paginate(10);
        // dd($users);
        return view('modules.attendence.mark-users-attendence',['users'=>$users,'message'=>$message]);
    }
    public function mark_all_attendence(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|string|in:present,absent',
        ]);

        $users = User::where('deleted', 0)->get();
        foreach ($users as $user) {
            $attendance = Attendence::where('user_id', $user->id)
                ->where('date', $validated['date'])
                ->first();

            if ($attendance) {
                $attendance->status = $validated['status'];
                $attendance->save();
            } else {
                Attendence::create([
                    'user_id' => $user->id,
                    'date' => $validated['date'],
                    'status' => $validated['status'],
                ]);
            }
        }

        $usersPaginated = User::where('deleted', 0)->paginate(10);
        $message = 'Attendance marked/updated for all users successfully.';
        return view('modules.attendence.mark-users-attendence', [
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

        $attendance = \App\Models\Attendence::create($validated);

        return response()->json($attendance, 201);
    }

    public function user_attendence_details(Request $request)
    {
        $id = $request->id;
        $data = \App\Models\Attendence::findOrFail($id);
        return view('modules.attendence.edit-user-attendence',['data'=>$data]);        
    }

    public function update_user_attendence_details(Request $request)
    {
        $id = $request->id;
        $data = \App\Models\Attendence::findOrFail($id);
        return view('modules.attendence.edit-user-attendence',['data'=>$data]);        
    }

    // public function update(Request $request)
    // {
    //     $attendance = \App\Models\Attendence::findOrFail($id);

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
    //     $attendance = \App\Models\Attendence::findOrFail($request->id);
    //     $attendance->delete();

    //     return response()->json(['message' => 'Attendance deleted successfully.']);
    // }
}
