<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;
use App\Interfaces\BaseControllerInterface;
use App\Models\AuditLog;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller implements BaseControllerInterface
{
    protected InventoryService $service;

    public function __construct(InventoryService $service)
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
        return view('modules.inventory.index', compact('data'));
    }
    
    private function exportToCsv($filters = [])
    {
        $items = $this->service->getAllForExport($filters);
        
        $filename = 'inventory_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Item Code', 'Item Name', 'Category', 'Price', 'Quantity', 'Total Value', 'Created At']);
            
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->item_code,
                    $item->item_name,
                    $item->category->name ?? 'N/A',
                    $item->item_price,
                    $item->item_qty,
                    $item->item_price * $item->item_qty,
                    $item->created_at
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
            return redirect()->route('inventory.index')->with('success', count($idArray) . ' items deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete items: ' . $e->getMessage());
        }
    }
    
    private function exportSelected($ids)
    {
        $idArray = explode(',', $ids);
        $items = $this->service->getByIds($idArray);
        
        $filename = 'selected_inventory_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Item Code', 'Item Name', 'Category', 'Price', 'Quantity', 'Total Value', 'Created At']);
            
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->item_code,
                    $item->item_name,
                    $item->category->name ?? 'N/A',
                    $item->item_price,
                    $item->item_qty,
                    $item->item_price * $item->item_qty,
                    $item->created_at
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Legacy method for backward compatibility
    public function index(Request $request)
    {
        return $this->getIndexView($request);
    }


    public function getDetailView(Request $request)
    {
        $data = $this->service->getDetailView($request->route('id'));
        return view('modules.inventory.show', compact('data'));
    }

    // Legacy method for backward compatibility
    public function show($id)
    {
        $data = $this->service->getDetailView($id);
        return view('modules.inventory.show', compact('data'));
    }

    public function getCreateView(Request $request)
    {
        $data = $this->service->getCreateView($request->all());
        return view('modules.inventory.new', compact('data'));
    }

    // Legacy method for backward compatibility
    public function create()
    {
        $data = $this->service->getCreateView([]);
        return view('modules.inventory.create', compact('data'));
    }

    public function submitCreateForm(Request $request)
    {
        $request->validate([
            'item_code' => 'required|unique:inventories',
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string|max:1000',
            'item_img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'item_price' => 'required|numeric|min:0',
            'item_qty' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);
        
        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            
            if($request->hasFile('item_img_path')) {
                $path = $request->file('item_img_path')->store('inventory_images', 'public');
                $data['item_img_path'] = $path;
            }
            
            $inventory = $this->service->submitCreateForm($data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $inventory->id,
                'auditable_type' => 'App\\Models\\Inventory',
                'user_id' => auth()->id(),
                'action' => 'created',
                'changes' => $data,
                'remarks' => 'Inventory item created'
            ]);
            
            return redirect()->route('inventory.index')->with('success', 'Inventory item created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create inventory item: ' . $e->getMessage())->withInput();
        }
    }

    // Legacy method for backward compatibility
    public function store(Request $request)
    {
        return $this->submitCreateForm($request);
    }

    public function getEditView(Request $request)
    {
        $data = $this->service->getEditView($request->route('id'));
        $categories = $this->service->getCategories();
        return view('modules.inventory.edit', compact('data', 'categories'));
    }

    // Legacy method for backward compatibility
    public function edit($id)
    {
        $data = $this->service->getEditView($id);
        $categories = $this->service->getCategories();
        return view('modules.inventory.edit', compact('data', 'categories'));
    }

    public function submitUpdateForm(Request $request)
    {
        $id = $request->route('id');
        $inventory = $this->service->getById($id);
        
        $request->validate([
            'item_code' => 'required|unique:inventories,item_code,' . $id,
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string|max:1000',
            'item_img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'item_price' => 'required|numeric|min:0',
            'item_qty' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);
        
        try {
            $data = $request->all();
            $oldData = $inventory->toArray();
            
            if($request->hasFile('item_img_path')) {
                $path = $request->file('item_img_path')->store('inventory_images', 'public');
                $data['item_img_path'] = $path;
            }
            
            $inventory = $this->service->submitUpdateForm($id, $data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $inventory->id,
                'auditable_type' => 'App\\Models\\Inventory',
                'user_id' => auth()->id(),
                'action' => 'updated',
                'changes' => ['old' => $oldData, 'new' => $data],
                'remarks' => 'Inventory item updated'
            ]);
            
            return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update inventory item: ' . $e->getMessage())->withInput();
        }
    }

    // Legacy method for backward compatibility
    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->submitUpdateForm($request);
    }

    public function submitDeleteForm(Request $request)
    {
        try {
            $id = $request->route('id');
            $inventory = $this->service->getById($id);
            $this->service->submitDeleteForm($id);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $inventory->id,
                'auditable_type' => 'App\\Models\\Inventory',
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'changes' => $inventory->toArray(),
                'remarks' => 'Inventory item deleted'
            ]);
            
            return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete inventory item: ' . $e->getMessage());
        }
    }

    // Legacy method for backward compatibility
    public function destroy($id)
    {
        $inventory = $this->service->getById($id);
        $this->service->submitDeleteForm($id);
        
        // Audit Log
        AuditLog::create([
            'auditable_id' => $inventory->id,
            'auditable_type' => 'App\\Models\\Inventory',
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'changes' => $inventory->toArray(),
            'remarks' => 'Inventory item deleted'
        ]);
        
        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully.');
    }

    public function stockIn(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        try {
            $result = $this->service->stockIn($id, $request->quantity);
            
            // Create transaction record
            InventoryTransaction::create([
                'inventory_id' => $id,
                'type' => 'in',
                'quantity' => $request->quantity,
                'user_id' => Auth::id(),
            ]);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $id,
                'auditable_type' => 'App\\Models\\Inventory',
                'user_id' => auth()->id(),
                'action' => 'stock_in',
                'changes' => ['before' => $result['before_qty'], 'after' => $result['after_qty']],
                'remarks' => 'Stock added: ' . $request->quantity
            ]);
            
            return back()->with('success', 'Stock added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }

    public function stockOut(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        try {
            $result = $this->service->stockOut($id, $request->quantity);
            
            // Create transaction record
            InventoryTransaction::create([
                'inventory_id' => $id,
                'type' => 'out',
                'quantity' => $request->quantity,
                'user_id' => Auth::id(),
            ]);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $id,
                'auditable_type' => 'App\\Models\\Inventory',
                'user_id' => auth()->id(),
                'action' => 'stock_out',
                'changes' => ['before' => $result['before_qty'], 'after' => $result['after_qty']],
                'remarks' => 'Stock removed: ' . $request->quantity
            ]);
            
            return back()->with('success', 'Stock removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
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
        return view('modules.inventory.dashboard', compact('data'));
    }
    
    public function getAuditLogs(Request $request)
    {
        $query = AuditLog::where('auditable_type', 'App\\Models\\Inventory')
                         ->with('user');
        
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
        
        return view('modules.inventory.audit_logs', compact('logs'));
    }

    // Legacy method for backward compatibility
    public function viewLogs($id)
    {
        $inventory = $this->service->getById($id);
        $query = AuditLog::where('auditable_id', $id)
                         ->where('auditable_type', 'App\\Models\\Inventory');

        if (request('action')) {
            $query->where('action', request('action'));
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('modules.inventory.logs', compact('inventory', 'logs'));
    }
}
