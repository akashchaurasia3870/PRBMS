<?php

namespace App\Http\Controllers;

use App\Services\ExpenseTrackerService;
use App\Interfaces\BaseControllerInterface;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ExpenseTrackerController extends Controller implements BaseControllerInterface
{
    protected ExpenseTrackerService $service;

    public function __construct(ExpenseTrackerService $service)
    {
        $this->service = $service;
    }

    public function getIndexView(Request $request)
    {
        // Handle export
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportToCsv($request->all());
        }
        
        // Handle bulk actions
        if ($request->has('bulk_delete')) {
            return $this->bulkDelete($request->bulk_delete);
        }
        
        if ($request->has('export_selected')) {
            return $this->exportSelected($request->export_selected);
        }
        
        $data = $this->service->getIndexView($request->all());
        return view('modules.expense_tracker.index', compact('data'));
    }
    
    private function exportToCsv($filters = [])
    {
        $expenses = $this->service->getAllForExport($filters);
        
        $filename = 'expenses_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Type', 'Description', 'Amount', 'Date', 'Created At']);
            
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->id,
                    $expense->type,
                    $expense->description,
                    $expense->amount,
                    $expense->expense_date,
                    $expense->created_at
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function bulkDelete($ids)
    {
        try {
            $idArray = explode(',', $ids);
            $this->service->bulkDelete($idArray);
            return redirect()->route('expense.v1.index')->with('success', count($idArray) . ' expenses deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete expenses: ' . $e->getMessage());
        }
    }
    
    private function exportSelected($ids)
    {
        $idArray = explode(',', $ids);
        $expenses = $this->service->getByIds($idArray);
        
        $filename = 'selected_expenses_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Type', 'Description', 'Amount', 'Date', 'Created At']);
            
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->id,
                    $expense->type,
                    $expense->description,
                    $expense->amount,
                    $expense->expense_date,
                    $expense->created_at
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function getCreateView(Request $request)
    {
        $data = $this->service->getCreateView($request->all());
        
        return view('modules.expense_tracker.new', compact('data'));
    }

    public function getEditView(Request $request)
    {
        $data = $this->service->getEditView($request->route('id'));
        $expenseTypes = $this->service->getExpenseTypes();
        return view('modules.expense_tracker.edit', compact('data', 'expenseTypes'));
    }

    public function getDetailView(Request $request)
    {
        $data = $this->service->getDetailView($request->route('id'));
        return view('modules.expense_tracker.show', compact('data'));
    }

    public function submitCreateForm(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'expense_date' => 'required|date'
        ]);
        
        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            $expense = $this->service->submitCreateForm($data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $expense->id,
                'auditable_type' => 'App\\Models\\ExpenseTracker',
                'user_id' => auth()->id(),
                'action' => 'created',
                'changes' => $data,
                'remarks' => 'Expense created'
            ]);
            
            return redirect()->route('expense.v1.index')->with('success', 'Expense created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create expense: ' . $e->getMessage())->withInput();
        }
    }

    public function submitUpdateForm(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'expense_date' => 'required|date'
        ]);
        
        try {
            $data = $request->all();
            $data['updated_by'] = auth()->id();
            $oldData = $this->service->getById($request->route('id'))->toArray();
            $expense = $this->service->submitUpdateForm($request->route('id'), $data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $expense->id,
                'auditable_type' => 'App\\Models\\ExpenseTracker',
                'user_id' => auth()->id(),
                'action' => 'updated',
                'changes' => ['old' => $oldData, 'new' => $data],
                'remarks' => 'Expense updated'
            ]);
            
            return redirect()->route('expense.v1.index')->with('success', 'Expense updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update expense: ' . $e->getMessage())->withInput();
        }
    }

    public function submitDeleteForm(Request $request)
    {
        try {
            $expense = $this->service->getById($request->route('id'));
            $this->service->submitDeleteForm($request->route('id'));
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $expense->id,
                'auditable_type' => 'App\\Models\\ExpenseTracker',
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'changes' => $expense->toArray(),
                'remarks' => 'Expense deleted'
            ]);
            
            return redirect()->route('expense.v1.index')->with('success', 'Expense deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete expense: ' . $e->getMessage());
        }
    }

    public function getIndexData(Request $request)
    {
        return response()->json($this->service->getIndexData($request->all()));
    }

    public function getDetailData(Request $request)
    {
        return response()->json($this->service->getDetailData($request->id));
    }
    
    public function getDashboard(Request $request)
    {
        $data = $this->service->getDashboardData($request->all());
        return view('modules.expense_tracker.dashboard', compact('data'));
    }
    
    public function getAuditLogs(Request $request)
    {
        $query = AuditLog::whereIn('auditable_type', [
            'App\\Models\\ExpenseTracker',
            'App\\Models\\ExpenseType'
        ])->with('user');
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('modules.expense_tracker.audit_logs', compact('logs'));
    }
}