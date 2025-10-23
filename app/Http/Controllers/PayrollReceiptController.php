<?php

namespace App\Http\Controllers;

use App\Models\PayrollReceipt;
use App\Models\User;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollReceiptController extends Controller
{
    protected PayrollService $service;

    public function __construct(PayrollService $service)
    {
        $this->service = $service;
    }
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'month', 'year', 'user_id']);
        $receipts = $this->service->getIndexData($filters);
        return view('modules.payroll_receipt.index', compact('receipts'));
    }
    
    public function dashboard()
    {
        $dashboardData = $this->service->getDashboardData();
        return view('modules.payroll_receipt.dashboard', $dashboardData);
    }

    // Show a single payroll receipt
    public function show($id)
    {
        $receipt = DB::selectOne("SELECT pr.*, u.name FROM payroll_receipts pr
                                  JOIN users u ON u.id = pr.user_id
                                  WHERE pr.id = ? AND pr.deleted = 0", [$id]);

        if (!$receipt) {
            abort(404, 'Payroll receipt not found.');
        }

        return view('modules.payroll_receipt.show', compact('receipt'));
    }

    // Generate payroll for one or all users
    public function generatePayroll(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;
        $user_id = $request->user_id;

        // Fallback to current month and year if not provided
        $month = !empty($month) ? $month : Carbon::now()->format('m');
        $year  = !empty($year)  ? $year  : Carbon::now()->format('Y');


        // Build user query
        $userQuery = User::where('deleted', 0);
        if ($user_id!='0') {
            $userQuery->where('id', $user_id);
        }
        $users = $userQuery->pluck('id');

        // Exclude users who already have payroll for this month/year
        $existingPayroll = DB::table('payroll_receipts')
            ->where('month', $month)
            ->where('year', $year)
            ->where('deleted', 0)
            ->whereIn('user_id', $users)
            ->pluck('user_id')
            ->toArray();

        $targetUsers = $users->diff($existingPayroll);

        if ($targetUsers->isEmpty()) {
            $users = User::where('deleted', 0)->get();
            return view('modules.payroll_receipt.generate', compact('users'))
            ->with([
                'status' => 'warning',
                'message' => 'Payroll already generated for selected user(s) for this month and year.',
                'code' => 409
            ]);
        }

        // Fetch attendance + salary structure for target users
        $records = DB::select("
            SELECT u.id as user_id, u.name, 
               v.total_days, v.present_count, 
               s.basic_salary, s.hra, s.da, s.other_allowance
            FROM users u
            LEFT JOIN attendance_view v ON u.id = v.id AND v.month = ? AND v.year = ?
            LEFT JOIN salary_structures s ON u.id = s.user_id
            WHERE u.id IN (" . implode(',', $targetUsers->toArray()) . ") AND u.deleted = 0 AND s.deleted = 0
        ", [$month, $year]);

        foreach ($records as $r) {
            $totalSalary = ($r->basic_salary + $r->hra + $r->da + $r->other_allowance);
            $workingDays = $r->total_days ?? 0;
            $presentDays = $r->present_count ?? 0;
            $leaveDays   = $workingDays - $presentDays;
            $netSalary   = $workingDays > 0 ? ($totalSalary / $workingDays) * $presentDays : 0;

            DB::insert("
            INSERT INTO payroll_receipts 
                (user_id, month, year, total_working_days, present_days, leave_days,
                 total_salary, net_salary, status, generated_at, created_by, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'generated', NOW(), 'System', NOW(), NOW())
            ", [$r->user_id, $month, $year, $workingDays, $presentDays, $leaveDays, $totalSalary, $netSalary]);
        }

        return redirect()->route('dashboard_payroll.index')->with('success', 'Payroll generated successfully.');
    }

    // Generate payroll for one or all users
    // Show form to generate payroll
    public function generateForm(Request $request)
    {
        $users = User::where('deleted', 0)->get();
    
        $month = $request->month;
        $year  = $request->year;
        $user_id = $request->user_id;

        // Fallback to current month and year if not provided
        $month = !empty($month) ? $month : Carbon::now()->format('m');
        $year  = !empty($year)  ? $year  : Carbon::now()->format('Y');


        // Build user query
        $userQuery = User::where('deleted', 0);
        if ($user_id!='0' && $user_id!=null) {
            $userQuery->where('id', $user_id);
        }
        $users = $userQuery->pluck('id');

        // Exclude users who already have payroll for this month/year
        $existingPayroll = DB::table('payroll_receipts')
            ->where('month', $month)
            ->where('year', $year)
            ->where('deleted', 0)
            ->whereIn('user_id', $users)
            ->pluck('user_id')
            ->toArray();

        $targetUsers = $users->diff($existingPayroll);


        // Fetch attendance + salary structure for target users
        // Fetch attendance + salary structure for target users
        $records = DB::table('users as u')
                    ->leftJoin('attendance_view as v', function ($join) use ($month, $year) {
                        $join->on('u.id', '=', 'v.id')
                            ->where('v.month', '=', $month)
                            ->where('v.year', '=', $year);
                    })
                    ->leftJoin('salary_structures as s', function ($join) {
                        $join->on('u.id', '=', 's.user_id')
                            ->where('s.deleted', '=', 0);
                    })
                    ->whereIn('u.id', $targetUsers->toArray())
                    ->where('u.deleted', '=', 0)
                    ->when($request->filled('search'), function ($query) use ($request) {
                        $query->where('u.name', 'like', '%' . $request->search . '%');
                    })
                   ->select(
                        'u.id as user_id',
                        'u.name',
                        'v.total_days',
                        'v.month',
                        'v.year',
                        'v.present_count as present_days',
                        DB::raw('(v.total_days - v.present_count) as leave_days'),
                        's.basic_salary',
                        's.hra',
                        's.da',
                        's.other_allowance',
                        DB::raw('(s.basic_salary + s.hra + s.da + s.other_allowance) as gross_salary'),
                        DB::raw('
                            CASE 
                                WHEN v.total_days > 0 
                                    THEN (s.basic_salary + s.hra + s.da + s.other_allowance) / v.total_days
                                ELSE 0
                            END as per_day_salary
                        '),
                        DB::raw('
                            CASE 
                                WHEN v.total_days > 0 
                                    THEN ((s.basic_salary + s.hra + s.da + s.other_allowance) / v.total_days) * v.present_count
                                ELSE 0
                            END as net_salary
                        ')
                    )
                    ->paginate(10)
                    ->appends($request->except('page'));

        return view('modules.payroll_receipt.generate',compact('users','records'));

    }

    // Mark as paid
    public function markAsPaid($id)
    {
        DB::update("UPDATE payroll_receipts SET status = 'paid', paid_at = NOW(), updated_by = 'System', updated_at = NOW()
                    WHERE id = ? AND deleted = 0", [$id]);

        return redirect()->back()->with('success', 'Marked as Paid.');
    }

    // Show edit form
    public function edit($id)
    {
        $receipt = DB::selectOne("SELECT * FROM payroll_receipts WHERE id = ? AND deleted = 0", [$id]);

        if (!$receipt) {
            abort(404, 'Payroll receipt not found.');
        }

        return view('modules.payroll_receipt.edit', compact('receipt'));
    }

    // Update payroll receipt
    public function update(Request $request, $id)
    {
        $presentDays = $request->present_days;
        $leaveDays   = $request->leave_days;
        $netSalary   = $request->net_salary;

        DB::update("
            UPDATE payroll_receipts 
            SET present_days = ?, leave_days = ?, net_salary = ?, updated_by = 'System', updated_at = NOW()
            WHERE id = ? AND deleted = 0
        ", [$presentDays, $leaveDays, $netSalary, $id]);

        return redirect()->route('dashboard_payroll.index')->with('success', 'Payroll updated.');
    }

    // Soft delete payroll receipt
    public function destroy(Request $request, $id)
    {
        DB::update("
            UPDATE payroll_receipts 
            SET deleted = 1, deleted_by = 'System', deleted_at = NOW()
            WHERE id = ?
        ", [$id]);

        return redirect()->route('dashboard_payroll.index')->with('success', 'Payroll deleted.');
    }

    // Export payroll (future)
    public function export(Request $request)
    {
        // Placeholder for PDF or Excel export implementation
        return redirect()->back()->with('info', 'Export functionality coming soon.');
    }
}


