<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{
    public function index()
    {
        $data = Role::where('deleted', '!=', 1)->paginate(10);
        return view('modules.roles.list-roles',['data'=>$data]);
    }

    public function detail(Request $request)
    {
        $data = Role::find($request->id);
        if (!$data) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        return view('modules.roles.edit-roles',['data'=>$data]);

    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|min:5|max:50',
            'role_desc' => 'required|string|min:5|max:255',
            'role_lvl' => 'required|integer|min:0|max:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = Role::create([
            'role_name' => $request->role_name,
            'role_desc' => $request->role_desc,
            'role_lvl' => $request->role_lvl,
        ]);

        return redirect()->route('dashboard_edit.roles', $data->id)->with('success', 'Role Created successfully!');
    }

    public function update(Request $request)
    {
        $data = Role::find($request->id);

        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|min:5|max:50',
            'role_desc' => 'required|string|min:5|max:255',
            'role_lvl' => 'required|integer|min:0|max:3',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dashboard_edit.roles', $request->id)->withErrors($validator)
            ->withInput();
        }
        
        $data->update([
            'role_name' => $request->role_name,
            'role_desc' => $request->role_desc,
            'role_lvl' => $request->role_lvl,
        ]);

        return redirect()->route('dashboard_edit.roles', $request->id)->with('success', 'Role updated successfully!');

    }

    public function destroy(Request $request)
    {
        $data = Role::find($request->id);
        if (!$data || $data->deleted_at) {
            redirect()->route('dashboard_list.roles',['error' => 'Role not found']);
        }

        $data->deleted_at = now();
        $data->deleted = true;
        $data->deleted_by = Auth::user()->id;
        $data->save();

        return redirect()->route('dashboard_list.roles',['message' => 'Role Deleted Successfully']);
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
