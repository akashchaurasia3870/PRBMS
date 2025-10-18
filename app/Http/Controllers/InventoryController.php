<?php
namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $query = Inventory::with('category');
        if (request('item_code')) {
            $query->where('item_code', 'like', '%' . request('item_code') . '%');
        }
        if (request('item_name')) {
            $query->where('item_name', 'like', '%' . request('item_name') . '%');
        }
        if (request('category')) {
            $query->whereHas('category', function($q) {
                $q->where('name', 'like', '%' . request('category') . '%');
            });
        }
        $inventories = $query->paginate(10)->appends(request()->except('page'));
        return view('modules.inventory.index', compact('inventories'));
    }

    public function show($id)
    {
        $inventory = Inventory::with('category')->findOrFail($id);
        return view('modules.inventory.show', compact('inventory'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('modules.inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => 'required|unique:inventories',
            'item_name' => 'required',
            'item_description' => 'nullable',
            'item_img_path' => 'nullable',
            'item_price' => 'required|integer',
            'item_qty' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        // dd($request);

        if($request->hasFile('item_img_path')) {
            $path = $request->file('item_img_path')->store('inventory_images', 'public');
            $validated['item_img_path'] = $path;
        }


        $validated['created_by'] = Auth::id();

        $inventory = Inventory::create($validated);

        return redirect()->route('inventory.index')
                         ->with('success', 'Inventory item created!');
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $categories = Category::all();
        return view('modules.inventory.edit', compact('inventory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);
        $validated = $request->validate([
            'item_code' => 'required|unique:inventories,item_code,' . $inventory->id,
            'item_name' => 'required',
            'item_description' => 'nullable',
            'item_img_path' => 'nullable',
            'item_price' => 'required|integer',
            'item_qty' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        if($request->hasFile('item_img_path')) {
            $path = $request->file('item_img_path')->store('inventory_images', 'public');
            $validated['item_img_path'] = $path;
        }


        $inventory->update($validated);

        return redirect()->route('inventory.index')
                         ->with('success', 'Inventory item updated.');
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->deleted_by = Auth::id();
        $inventory->save();
        $inventory->delete();

        return redirect()->route('inventory.index')
                         ->with('success', 'Inventory item deleted.');
    }

    public function stockIn(Request $request, Inventory $inventory)
    {
        $validated = $request->validate(['quantity' => 'required|integer|min:1']);
        $beforeQty = $inventory->item_qty;
        $inventory->item_qty += $validated['quantity'];
        $inventory->save();

        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'type' => 'in',
            'quantity' => $validated['quantity'],
            'user_id' => Auth::id(),
        ]);

        $this->logAudit($inventory,'stock_in',$beforeQty,$inventory->item_qty,$validated['quantity']);

        return back()->with('success', 'Stock added successfully.');
    }

    public function stockOut(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $beforeQty = $inventory->item_qty;
        if ($inventory->item_qty < $validated['quantity']) {
            return back()->with('error', 'Not enough stock!');
        }

        $inventory->item_qty -= $validated['quantity'];
        $inventory->save();

        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'type' => 'out',
            'quantity' => $validated['quantity'],
            'user_id' => Auth::id(),
        ]);

        $this->logAudit($inventory,'stock_out',$beforeQty,$inventory->item_qty,$validated['quantity']);

        return back()->with('success', 'Stock removed successfully.');
    }

    public function viewLogs(Inventory $inventory)
    {
        $query = AuditLog::query();
        $query->where('auditable_id', $inventory->id);

        if (request('auditable_type')) {
            $query->where('auditable_type', request('auditable_type'));
        }
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        if (request('action')) {
            $query->where('action', request('action'));
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('modules.inventory.logs', compact('inventory', 'logs'));
    }

    function logAudit($model,$action,$before,$after,$remarks )
    {
        AuditLog::create([
            'auditable_id' => $model->id,
            'auditable_type' => get_class($model),
            'user_id' => Auth::id(),
            'action' => $action,
            'changes' => ['before' => $before, 'after' => $after],
            'remarks' => $remarks,
            'created_at' => now(),
        ]);
    }


}
