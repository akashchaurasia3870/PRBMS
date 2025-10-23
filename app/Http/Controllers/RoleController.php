<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Interfaces\BaseControllerInterface;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller implements BaseControllerInterface
{
    protected RoleService $service;

    public function __construct(RoleService $service)
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
        return view('modules.roles.index', compact('data'));
    }
    
    private function exportToCsv($filters = [])
    {
        $roles = $this->service->getAllForExport($filters);
        
        $filename = 'roles_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($roles) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Role Name', 'Description', 'Level', 'Created At']);
            
            foreach ($roles as $role) {
                fputcsv($file, [
                    $role->id,
                    $role->role_name,
                    $role->role_desc,
                    $role->role_lvl,
                    $role->created_at
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
            return redirect()->route('dashboard_list.roles')->with('success', count($idArray) . ' roles deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard_list.roles')->with('error', 'Failed to delete roles: ' . $e->getMessage());
        }
    }
    
    private function exportSelected($ids)
    {
        $idArray = explode(',', $ids);
        $roles = $this->service->getByIds($idArray);
        
        $filename = 'selected_roles_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($roles) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Role Name', 'Description', 'Level', 'Created At']);
            
            foreach ($roles as $role) {
                fputcsv($file, [
                    $role->id,
                    $role->role_name,
                    $role->role_desc,
                    $role->role_lvl,
                    $role->created_at
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function getCreateView(Request $request)
    {
        $data = $this->service->getCreateView($request->all());
        return view('modules.roles.new', compact('data'));
    }

    public function getEditView(Request $request)
    {
        $data = $this->service->getEditView($request->route('id'));
        return view('modules.roles.edit', compact('data'));
    }

    public function getDetailView(Request $request)
    {
        $data = $this->service->getDetailView($request->route('id'));
        return view('modules.roles.show', compact('data'));
    }

    public function submitCreateForm(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|min:3|max:50|unique:roles,role_name',
            'role_desc' => 'required|string|min:5|max:255',
            'role_lvl' => 'required|integer|min:0|max:3',
        ]);
        
        try {
            $data = $request->all();
            $role = $this->service->submitCreateForm($data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $role->id,
                'auditable_type' => 'App\\Models\\Role',
                'user_id' => auth()->id(),
                'action' => 'created',
                'changes' => $data,
                'remarks' => 'Role created'
            ]);
            
            return redirect()->route('dashboard_list.roles')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create role: ' . $e->getMessage())->withInput();
        }
    }

    public function submitUpdateForm(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|min:3|max:50|unique:roles,role_name,' . $request->route('id'),
            'role_desc' => 'required|string|min:5|max:255',
            'role_lvl' => 'required|integer|min:0|max:3',
        ]);
        
        try {
            $data = $request->all();
            $oldData = $this->service->getById($request->route('id'))->toArray();
            $role = $this->service->submitUpdateForm($request->route('id'), $data);
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $role->id,
                'auditable_type' => 'App\\Models\\Role',
                'user_id' => auth()->id(),
                'action' => 'updated',
                'changes' => ['old' => $oldData, 'new' => $data],
                'remarks' => 'Role updated'
            ]);
            
            return redirect()->route('dashboard_list.roles')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update role: ' . $e->getMessage())->withInput();
        }
    }

    public function submitDeleteForm(Request $request)
    {
        try {
            $role = $this->service->getById($request->route('id'));
            $this->service->submitDeleteForm($request->route('id'));
            
            // Audit Log
            AuditLog::create([
                'auditable_id' => $role->id,
                'auditable_type' => 'App\\Models\\Role',
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'changes' => $role->toArray(),
                'remarks' => 'Role deleted'
            ]);
            
            return redirect()->route('dashboard_list.roles')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard_list.roles')->with('error', 'Failed to delete role: ' . $e->getMessage());
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

    public function add_users_list(Request $request){
        $role_id = $request->id;
        $lvl = $request->lvl;
        $role_name = $request->role_name;
        // $users = new UserController();
        // $data = $users->index();

        $data = DB::table('users')
            ->leftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->select('users.name','users.id','users.email','users.created_at', 'user_roles.role_id','user_roles.role_lvl','user_roles.id as r_id')
            // ->where('user_roles.deleted', '!=', 1)
            // ->where('users.deleted','!=',1)
            ->paginate(10);
        // dd($data);
        return view('modules.roles.add-users',['users'=>$data,'role_id'=>$role_id,'lvl'=>$lvl,'role_name'=>$role_name]);
    }

    public function add_users_data(Request $request){
        $validator = Validator::make($request->all(),[
            'role_id'=>'required',
            'user_id'=>'required',
            'lvl'=>'required',
            'role_name'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the user already has this role
        $exists = UserRoles::where('role_id', $request->role_id)
            ->where('user_id', $request->user_id)
            ->exists();

        if ($exists) {
            return redirect()->route('dashboard_add_users.roles', [
            'id' => $request->role_id,
            'lvl' => $request->lvl
            ])->with('error', 'User already has this role.');
        }

        $data = UserRoles::create([
            'role_id' => $request->role_id,
            'user_id' => $request->user_id,
            'role_lvl' => $request->lvl
        ]);
        
        return redirect()->route('dashboard_add_users.roles',['id'=>$request->role_id,'lvl'=>$request->lvl,'role_name'=>$request->role_name])->with('success', 'User Added successfully!');
    }
    
    public function destroy_user_role(Request $request){
        $validator = Validator::make($request->all(),[
            'id'=>'required',
            'role_id'=>'required',
            'user_id'=>'required',
            'lvl'=>'required',
            'role_name'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = UserRoles::find($request->id);
        if (!$data || $data->deleted_at) {
            redirect()->route('dashboard_add_users.roles',['error' => 'Role not found']);
        }

        $data->deleted_at = now();
        $data->deleted = true;
        $data->deleted_by = Auth::user()->id;
        $data->save();
        
        return redirect()->route('dashboard_add_users.roles',['id'=>$request->role_id,'lvl'=>$request->lvl,'role_name'=>$request->role_name])->with('success', 'Role Removed Successfully!');
    }
}
