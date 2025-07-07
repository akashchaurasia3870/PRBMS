<?php

namespace App\Http\Controllers;

use App\Models\SalaryStructure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryStructureController extends Controller
{
    public function index()
    {
        $structures = SalaryStructure::with('user')->where('deleted', '0')->paginate(10);
        return view('modules.salary_structure.index', compact('structures'));
    }

    public function create()
    {
        $users = User::all();
        return view('modules.salary_structure.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'basic_salary' => 'required|numeric',
        ]);

        SalaryStructure::create($request->all());

        return redirect()->route('dashboard_salary.index')
                         ->with('success', 'Salary structure created successfully.');
    }

    public function edit(Request $req)
    {
        $salary_structure = DB::table('salary_structures as ss')
            ->join('users as u', function($join) {
                $join->on('u.id', '=', 'ss.user_id')
                     ->where('u.deleted', '0');
            })
            ->select('ss.*', 'u.name')
            ->where('ss.id', $req->id)
            ->where('ss.deleted', '0')
            ->first();

        if (!$salary_structure) {
            abort(404);
        }

        return view('modules.salary_structure.edit', [
            'salary_structure' => $salary_structure,
            'user' => (object)['name' => $salary_structure->name]
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'basic_salary' => 'required|numeric',
        ]);

        SalaryStructure::where('id', $request->id)->update([
            'basic_salary'     => $request->basic_salary,
            'hra'              => $request->hra,
            'da'               => $request->da,
            'other_allowance'  => $request->other_allowance,
        ]);

        return redirect()->route('dashboard_salary.index')
                         ->with('success', 'Salary structure updated successfully.');
    }

    public function delete(Request $req)
    {
        SalaryStructure::where('id', $req->id)->update(['deleted' => '1']);

        return redirect()->route('dashboard_salary.index')
                         ->with('success', 'Salary structure deleted.');
    }
}
