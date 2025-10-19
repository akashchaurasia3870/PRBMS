<?php

namespace App\Http\Controllers;

use App\Services\ExpenseTrackerTypeService;
use App\Interfaces\BaseControllerInterface;
use App\Models\AuditLog;
use App\Models\ExpenseType;
use Illuminate\Http\Request;

class ExpenseTrackerTypeController extends Controller implements BaseControllerInterface
{
    protected ExpenseTrackerTypeService $service;

    public function __construct(ExpenseTrackerTypeService $service)
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
        return view('modules.expense_type.index', compact('data'));
    }
    
    private function exportToCsv($filters = [])
    {
        $expenseTypes = $this->service->getAllForExport($filters);
        
        $filename = 'expense_types_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($expenseTypes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Type', 'Description', 'Created At']);
            
            foreach ($expenseTypes as $type) {
                fputcsv($file, [
                    $type->id,
                    $type->type,
                    $type->description,
                    $type->created_at
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
            return redirect()->route('expense_type.v1.index')->with('success', count($idArray) . ' expense types deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('expense_type.v1.index')->with('error', 'Failed to delete expense types: ' . $e->getMessage());
        }
    }
    
    private function exportSelected($ids)
    {
        $idArray = explode(',', $ids);
        $expenseTypes = $this->service->getByIds($idArray);
        
        $filename = 'selected_expense_types_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($expenseTypes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Type', 'Description', 'Created At']);
            
            foreach ($expenseTypes as $type) {
                fputcsv($file, [
                    $type->id,
                    $type->type,
                    $type->description,
                    $type->created_at
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function getCreateView(Request $request)
    {
        $data = $this->service->getCreateView($request->all());
        return view('modules.expense_type.new', compact('data'));
    }

    public function getEditView(Request $request)
    {
        $data = $this->service->getEditView($request->route('id'));
        return view('modules.expense_type.edit', compact('data'));
    }

    public function getDetailView(Request $request)
    {
        $data = $this->service->getDetailView($request->route('id'));
        return view('modules.expense_type.show', compact('data'));
    }

    public function submitCreateForm(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255|unique:expense_type,type',
            'description' => 'required|string|max:1000'
        ]);
        
        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            $expenseType = $this->service->submitCreateForm($data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $expenseType->id,
                'auditable_type' => 'App\\Models\\ExpenseType',
                'user_id' => auth()->id(),
                'action' => 'created',
                'changes' => $data,
                'remarks' => 'Expense type created'
            ]);
            
            return redirect()->route('expense_type.v1.index')->with('success', 'Expense type created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create expense type: ' . $e->getMessage())->withInput();
        }
    }

    public function submitUpdateForm(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255|unique:expense_type,type,' . $request->route('id'),
            'description' => 'required|string|max:1000'
        ]);
        
        try {
            $data = $request->all();
            $data['updated_by'] = auth()->id();
            $oldData = $this->service->getById($request->route('id'))->toArray();
            $expenseType = $this->service->submitUpdateForm($request->route('id'), $data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $expenseType->id,
                'auditable_type' => 'App\\Models\\ExpenseType',
                'user_id' => auth()->id(),
                'action' => 'updated',
                'changes' => ['old' => $oldData, 'new' => $data],
                'remarks' => 'Expense type updated'
            ]);
            
            return redirect()->route('expense_type.v1.index')->with('success', 'Expense type updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update expense type: ' . $e->getMessage())->withInput();
        }
    }

    public function submitDeleteForm(Request $request)
    {
        try {
            $expenseType = $this->service->getById($request->route('id'));
            
            // Check if expense type is being used
            if ($expenseType->hasExpenses()) {
                return redirect()->route('expense_type.v1.index')->with('error', 'Cannot delete expense type "' . $expenseType->type . '" as it is being used by existing expenses.');
            }
            
            $this->service->submitDeleteForm($request->route('id'));
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $expenseType->id,
                'auditable_type' => 'App\\Models\\ExpenseType',
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'changes' => $expenseType->toArray(),
                'remarks' => 'Expense type deleted'
            ]);
            
            return redirect()->route('expense_type.v1.index')->with('success', 'Expense type deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('expense_type.v1.index')->with('error', 'Failed to delete expense type: ' . $e->getMessage());
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
}