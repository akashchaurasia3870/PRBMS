<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Interfaces\BaseControllerInterface;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller implements BaseControllerInterface
{
    protected UserService $service;

    public function __construct(UserService $service)
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
        return view('modules.users.index', compact('data'));
    }
    
    private function exportToCsv($filters = [])
    {
        $users = $this->service->getAllForExport($filters);
        
        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Created At', 'Status']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at,
                    $user->deleted ? 'Inactive' : 'Active'
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
            return redirect()->route('dashboard_list.user')->with('success', count($idArray) . ' users deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard_list.user')->with('error', 'Failed to delete users: ' . $e->getMessage());
        }
    }
    
    private function exportSelected($ids)
    {
        $idArray = explode(',', $ids);
        $users = $this->service->getByIds($idArray);
        
        $filename = 'selected_users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Created At', 'Status']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at,
                    $user->deleted ? 'Inactive' : 'Active'
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function getCreateView(Request $request)
    {
        $data = $this->service->getCreateView($request->all());
        return view('modules.users.new', compact('data'));
    }

    public function getEditView(Request $request)
    {
        $data = $this->service->getEditView($request->route('id'));
        return view('modules.users.edit', compact('data'));
    }

    public function getDetailView(Request $request)
    {
        $data = $this->service->getDetailView($request->route('id'));
        return view('modules.users.show', compact('data'));
    }







    public function submitCreateForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        try {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            $data['email_verified_at'] = now();
            $user = $this->service->submitCreateForm($data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $user->id,
                'auditable_type' => 'App\\Models\\User',
                'user_id' => auth()->id(),
                'action' => 'created',
                'changes' => ['name' => $user->name, 'email' => $user->email],
                'remarks' => 'User created'
            ]);
            
            return redirect()->route('dashboard_list.user')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage())->withInput();
        }
    }

    public function submitUpdateForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $request->route('id'),
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        try {
            $data = $request->all();
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            
            $oldData = $this->service->getById($request->route('id'))->toArray();
            $user = $this->service->submitUpdateForm($request->route('id'), $data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $user->id,
                'auditable_type' => 'App\\Models\\User',
                'user_id' => auth()->id(),
                'action' => 'updated',
                'changes' => ['old' => $oldData, 'new' => $data],
                'remarks' => 'User updated'
            ]);
            
            return redirect()->route('dashboard_list.user')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage())->withInput();
        }
    }

    public function submitDeleteForm(Request $request)
    {
        try {
            $user = $this->service->getById($request->route('id'));
            $this->service->submitDeleteForm($request->route('id'));
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $user->id,
                'auditable_type' => 'App\\Models\\User',
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'changes' => $user->toArray(),
                'remarks' => 'User deleted'
            ]);
            
            return redirect()->route('dashboard_list.user')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard_list.user')->with('error', 'Failed to delete user: ' . $e->getMessage());
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
