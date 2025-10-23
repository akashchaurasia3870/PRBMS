<?php

namespace App\Services;

use App\Models\PayrollReceipt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function getIndexData(array $filters = [])
    {
        $query = PayrollReceipt::with('user');
        
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }
        
        if (!empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }
        
        if (!empty($filters['month'])) {
            $query->byMonth($filters['month']);
        }
        
        if (!empty($filters['year'])) {
            $query->byYear($filters['year']);
        }
        
        if (!empty($filters['user_id'])) {
            $query->byUser($filters['user_id']);
        }
        
        return $query->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->paginate(10);
    }
    
    public function getDashboardData(array $filters = [])
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $totalPayrolls = PayrollReceipt::count();
        $currentMonthPayrolls = PayrollReceipt::currentMonth()->count();
        $paidPayrolls = PayrollReceipt::paid()->count();
        $pendingPayrolls = PayrollReceipt::pending()->count();
        
        $totalSalaryPaid = PayrollReceipt::paid()->sum('net_salary');
        $currentMonthSalary = PayrollReceipt::currentMonth()->sum('net_salary');
        
        $recentPayrolls = PayrollReceipt::with('user')
                                     ->orderBy('created_at', 'desc')
                                     ->limit(5)
                                     ->get();
        
        $pendingPayrollsList = PayrollReceipt::with('user')
                                           ->pending()
                                           ->orderBy('created_at', 'desc')
                                           ->limit(10)
                                           ->get();
        
        $monthlyStats = PayrollReceipt::select(
                            DB::raw('month'),
                            DB::raw('year'),
                            DB::raw('COUNT(*) as count'),
                            DB::raw('SUM(net_salary) as total_amount')
                        )
                        ->where('year', $currentYear)
                        ->groupBy('month', 'year')
                        ->orderBy('month')
                        ->get();
        
        return [
            'total_payrolls' => $totalPayrolls,
            'current_month_payrolls' => $currentMonthPayrolls,
            'paid_payrolls' => $paidPayrolls,
            'pending_payrolls' => $pendingPayrolls,
            'total_salary_paid' => $totalSalaryPaid,
            'current_month_salary' => $currentMonthSalary,
            'recent_payrolls' => $recentPayrolls,
            'pending_payrolls_list' => $pendingPayrollsList,
            'monthly_stats' => $monthlyStats
        ];
    }
    
    public function getUsers()
    {
        return User::where('deleted', 0)->get();
    }
    
    public function getStatuses()
    {
        return [
            'generated' => 'Generated',
            'paid' => 'Paid',
            'pending' => 'Pending'
        ];
    }
    
    public function markAsPaid(int $id)
    {
        $payroll = PayrollReceipt::findOrFail($id);
        $payroll->update([
            'status' => 'paid',
            'paid_at' => now(),
            'updated_by' => auth()->id() ?? 'System'
        ]);
        
        return $payroll;
    }
    
    public function bulkMarkAsPaid(array $ids)
    {
        return PayrollReceipt::whereIn('id', $ids)->update([
            'status' => 'paid',
            'paid_at' => now(),
            'updated_by' => auth()->id() ?? 'System'
        ]);
    }
    
    public function getPayrollStatistics(array $filters = [])
    {
        $query = PayrollReceipt::query();
        
        if (!empty($filters['year'])) {
            $query->byYear($filters['year']);
        }
        
        if (!empty($filters['month'])) {
            $query->byMonth($filters['month']);
        }
        
        $payrolls = $query->get();
        
        return [
            'total' => $payrolls->count(),
            'paid' => $payrolls->where('status', 'paid')->count(),
            'pending' => $payrolls->where('status', 'generated')->count(),
            'total_amount' => $payrolls->sum('net_salary'),
            'paid_amount' => $payrolls->where('status', 'paid')->sum('net_salary'),
            'pending_amount' => $payrolls->where('status', 'generated')->sum('net_salary'),
            'by_status' => $payrolls->groupBy('status')->map->count()
        ];
    }
}