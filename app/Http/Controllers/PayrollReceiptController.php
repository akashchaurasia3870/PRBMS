<?php

namespace App\Http\Controllers;

use App\Models\PayrollReceipt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollReceiptController extends Controller
{
    // List all payroll receipts with optional filters
    public function index(Request $request)
    {
        $month  = $request->input('month');
        $year   = $request->input('year');
        $status = $request->input('status');
        $userId = $request->input('user_id');

        $receipts = DB::table('payroll_receipts as pr')
            ->join('users as u', 'u.id', '=', 'pr.user_id')
            ->select('pr.*', 'u.name')
            ->where('pr.deleted', 0);

        if ($month)  $receipts->where('pr.month', $month);
        if ($year)   $receipts->where('pr.year', $year);
        if ($status) $receipts->where('pr.status', $status);
        if ($userId) $receipts->where('pr.user_id', $userId);
        if ($request->filled('search')) {
            $receipts->where('name', 'like', '%' . $request->search . '%');
        }


        $receipts = $receipts->orderByDesc('pr.year')
            ->orderByDesc('pr.month')
            ->paginate(10)->appends($request->except('page'));

        return view('modules.payroll_receipt.index', compact('receipts'));
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

    // Show form to generate payroll
    public function generateForm()
    {
        $users = User::where('deleted', 0)->get();
        return view('modules.payroll_receipt.generate',compact('users'));
    }

    // Generate payroll for one or all users
    public function generatePayroll(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;
        $all_users = $request->all_users;
        $user_id = $request->user_id;

        // Build user query
        $userQuery = User::where('deleted', 0);
        if (!$all_users) {
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


