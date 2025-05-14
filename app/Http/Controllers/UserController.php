<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\UserInfoService;
class UserController extends Controller
{
    protected $userInfoService;

    public function __construct(UserInfoService $userInfoService){
        $this->userInfoService = $userInfoService;
    }

    public function index()
    {
        $users = User::where('deleted', '!=', 1)->paginate(10);
        return view('modules.users.list-users',['users'=>$users]);
    }

    public function detail(Request $request)
    {
        $user = User::find($request->id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return view('modules.users.edit-user',['user'=>$user]);

    }

    public function get_user_details(Request $request)
    {
        $data = $this->userInfoService->getUserDetails($request->id);
        // dd($data);
        if (!$data) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return view('modules.users.details-user',['data'=>$data]);

    }

    public function update_contact(Request $request)
    {
        $data = $this->userInfoService->updateContact($request);
        // dd($data);
        if (!$data) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return redirect()->route('dashboard_details.user', ['id' => $request->user_id])
            ->with('success', 'Contact updated successfully!')
            ->with('data', $data);

    }

    public function update_documents(Request $request)
    {
        // Retrieve files from the request
        $files = $request->files; // 'files' should match the input name in your form
        $data = $this->userInfoService->updateDocuments($request, $files);

        if (!$data) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return redirect()->route('dashboard_details.user', ['id' => $request->user_id])
            ->with('success', 'Documents updated successfully!')
            ->with('data', $data);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verified_at' => now(),
        ]);

        // return response()->json($user, 201);
        return redirect()->route('dashboard_edit.user', $user->id)->with('success', 'User Created successfully!');

    }

    public function update(Request $request)
    {
        $user = User::find($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|string|min:8|confirmed',
        ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        //     // return view('modules.users.edit-user',['errors' => $validator->errors()]);
        // }

        if ($validator->fails()) {
            return redirect()->route('dashboard_edit.user', $request->id)->withErrors($validator)
            ->withInput();
        }
        
        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('dashboard_edit.user', $request->id)->with('success', 'User updated successfully!');

    }

    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        if (!$user || $user->deleted_at) {
            redirect()->route('dashboard_list.user',['error' => 'User not found']);
        }

        $user->deleted_at = now();
        $user->deleted = true;
        $user->deleted_by = Auth::user()->id;
        $user->save();

        return redirect()->route('dashboard_list.user',['message' => 'User Deleted Successfully']);
    }
}
