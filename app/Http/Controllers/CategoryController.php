<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Interfaces\BaseControllerInterface;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class CategoryController extends Controller implements BaseControllerInterface
{
    protected CategoryService $service;

    public function __construct(CategoryService $service)
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
        return view('modules.category.index', compact('data'));
    }
    
    private function exportToCsv($filters = [])
    {
        $categories = $this->service->getAllForExport($filters);
        
        $filename = 'categories_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Code', 'Description', 'Created At']);
            
            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    $category->code,
                    $category->description,
                    $category->created_at
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
            return redirect()->route('category.index')->with('success', count($idArray) . ' categories deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete categories: ' . $e->getMessage());
        }
    }
    
    private function exportSelected($ids)
    {
        $idArray = explode(',', $ids);
        $categories = $this->service->getByIds($idArray);
        
        $filename = 'selected_categories_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Code', 'Description', 'Created At']);
            
            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    $category->code,
                    $category->description,
                    $category->created_at
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function getCreateView(Request $request)
    {
        $data = $this->service->getCreateView($request->all());
        return view('modules.category.create', compact('data'));
    }

    public function getEditView(Request $request)
    {
        $data = $this->service->getEditView($request->route('id'));
        return view('modules.category.edit', compact('data'));
    }

    public function getDetailView(Request $request)
    {
        $data = $this->service->getDetailView($request->route('id'));
        return view('modules.category.show', compact('data'));
    }

    public function submitCreateForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:categories,code',
            'description' => 'nullable|string|max:1000'
        ]);
        
        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            $category = $this->service->submitCreateForm($data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $category->id,
                'auditable_type' => 'App\\Models\\Category',
                'user_id' => auth()->id(),
                'action' => 'created',
                'changes' => $data,
                'remarks' => 'Category created'
            ]);
            
            return redirect()->route('category.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create category: ' . $e->getMessage())->withInput();
        }
    }

    public function submitUpdateForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:categories,code,' . $request->route('id'),
            'description' => 'nullable|string|max:1000'
        ]);
        
        try {
            $data = $request->all();
            $data['updated_by'] = auth()->id();
            $oldData = $this->service->getById($request->route('id'))->toArray();
            $category = $this->service->submitUpdateForm($request->route('id'), $data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $category->id,
                'auditable_type' => 'App\\Models\\Category',
                'user_id' => auth()->id(),
                'action' => 'updated',
                'changes' => ['old' => $oldData, 'new' => $data],
                'remarks' => 'Category updated'
            ]);
            
            return redirect()->route('category.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage())->withInput();
        }
    }

    public function submitDeleteForm(Request $request)
    {
        try {
            $category = $this->service->getById($request->route('id'));
            
            // Check if category is being used
            if ($category->inventories()->count() > 0) {
                return redirect()->route('category.index')->with('error', 'Cannot delete category "' . $category->name . '" as it is being used by existing inventory items.');
            }
            
            $this->service->submitDeleteForm($request->route('id'));
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $category->id,
                'auditable_type' => 'App\\Models\\Category',
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'changes' => $category->toArray(),
                'remarks' => 'Category deleted'
            ]);
            
            return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('category.index')->with('error', 'Failed to delete category: ' . $e->getMessage());
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

    // Legacy methods for backward compatibility
    public function index(Request $request)
    {
        return $this->getIndexView($request);
    }

    public function create()
    {
        $data = $this->service->getCreateView([]);
        return view('modules.category.create', compact('data'));
    }

    public function store(Request $request)
    {
        return $this->submitCreateForm($request);
    }

    public function show($id)
    {
        $data = $this->service->getDetailView($id);
        return view('modules.category.show', compact('data'));
    }

    public function edit($id)
    {
        $data = $this->service->getEditView($id);
        return view('modules.category.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->submitUpdateForm($request);
    }

    public function destroy($id)
    {
        $category = $this->service->getById($id);
        
        // Check if category is being used
        if ($category->inventories()->count() > 0) {
            return redirect()->route('category.index')->with('error', 'Cannot delete category "' . $category->name . '" as it is being used by existing inventory items.');
        }
        
        $this->service->submitDeleteForm($id);
        
        // Audit Log
        AuditLog::create([
            'auditable_id' => $category->id,
            'auditable_type' => 'App\\Models\\Category',
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'changes' => $category->toArray(),
            'remarks' => 'Category deleted'
        ]);
        
        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
    
    public function getAuditLogs(Request $request)
    {
        $query = AuditLog::where('auditable_type', 'App\\Models\\Category')
                         ->with('user');
        
        if ($request->filled('category')) {
            $query->where('auditable_id', $request->category);
        }
        
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
        
        return view('modules.category.audit_logs', compact('logs'));
    }
}
