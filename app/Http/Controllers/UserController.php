<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        return view('modules.users.create-user');
    }

    public function edit(Request $request)
    {
        return view('modules.users.edit-user');
    }

    public function index()
    {
        return view('modules.users.list-users');
    }

    public function show(Request $request, $id)
    {
        return view('modules.users.view-user-details', ['id' => $id]);
    }

    public function destroy(Request $request, $id)
    {
        // You might want to add some logic here to actually delete the user.
        return redirect()->route('modules.users.create-user');
    }
}
