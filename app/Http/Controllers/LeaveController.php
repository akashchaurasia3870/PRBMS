<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;

class LeaveController extends Controller
{

    public function index()
    {
        $leaves = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->paginate(10);
        return view('modules.leave.leave-request',['leaves'=>$leaves]);
    }

    public function leave_request_view(Request $request)
    {
        $users = User::where('deleted', 0)->get();
        return view('modules.leave.apply-leave',['users'=>$users]);
    }

    public function create_leave_request(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $leave = Leave::create($validated);
        $leaves = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->paginate(10);
        return view('modules.leave.leave-request',['leaves'=>$leaves]);
    }

    public function get_leave_info(Request $request)
    {
        $leave = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->where('users_leave.id', '=', $request->id)
            ->first();
        
        return view('modules.leave.leave-info-detail',['data'=>$leave]);
    }

    public function edit_leave_info(Request $request)
    {
        
        $validated = $request->validate([
            'id' => 'required|integer',
            'leave_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'reason' => 'string',
            'description' => 'string',
        ]);

        $leave = Leave::findOrFail($request->id);
        $leave->update($validated);

        
        $leave = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->where('users_leave.id', '=', $request->id)
            ->first();
        
        return view('modules.leave.leave-info-detail',['data'=>$leave,'message' => 'Leave Updated successfully']);

    }

    public function destroy_leave_info(Request $request)
    {
        $leave = Leave::findOrFail($request->id);
        $leave->update(['deleted' => '1']);

        $leaves = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->paginate(10);
        return view('modules.leave.leave-request',['leaves'=>$leaves]);
    }

    public function approve_leave_status(Request $request)
    {
        $leave = Leave::findOrFail($request->id);
        $leave->update(['status' => 'approved']);

        $leaves = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->paginate(10);
        return view('modules.leave.leave-request',['leaves'=>$leaves]);
    }
    public function reject_leave_status(Request $request)
    {
        $reason = $request->reason;
        $leave = Leave::findOrFail($request->id);
        $leave->status = 'rejected';
        $leave->rejection_reason = $reason;
        $leave->save();

        $leaves = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->paginate(10);
        return view('modules.leave.leave-request',['leaves'=>$leaves]);
    }
}

