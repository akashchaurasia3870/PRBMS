<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LeaveService;
use App\Models\Leave;
use App\Models\User;

class LeaveController extends Controller
{
    protected LeaveService $service;

    public function __construct(LeaveService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $filters = request()->only(['user', 'status', 'leave_type', 'from_date', 'to_date', 'days_min', 'days_max', 'search']);
        $leaves = $this->service->search($filters, 10);
        return view('modules.leave.index', compact('leaves'));
    }
    
    public function dashboard()
    {
        $dashboardData = $this->service->getDashboardData();
        return view('modules.leave.dashboard', $dashboardData);
    }

    public function leave_request_view(Request $request)
    {
        $users = $this->service->getUsers();
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

        $this->service->submitCreateForm($validated);
        return redirect()->route('dashboard_leave.index')->with('success', 'Leave request submitted successfully');
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

        $this->service->update($request->id, $validated);
        
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
        $this->service->destroy($request->id);

        $leaves = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->paginate(10);
        return view('modules.leave.leave-request',['leaves'=>$leaves]);
    }

    public function approve_leave_status(Request $request)
    {
        $this->service->approveLeave($request->id);

        $leaves = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->paginate(10);
        return view('modules.leave.leave-request',['leaves'=>$leaves]);
    }
    public function reject_leave_status(Request $request)
    {
        $this->service->rejectLeave($request->id, $request->reason);

        $leaves = Leave::join('users', 'users_leave.user_id', '=', 'users.id')
            ->select('users_leave.*', 'users.name')
            ->where('users_leave.deleted', '=', '0')
            ->where('users.deleted', '=', '0')
            ->paginate(10);
        return view('modules.leave.leave-request',['leaves'=>$leaves]);
    }
}

