<?php

namespace App\Http\Controllers;

use App\Models\SalaryStructure;
use App\Models\User;
use App\Services\SalaryStructureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryStructureController extends Controller
{
    protected SalaryStructureService $service;

    public function __construct(SalaryStructureService $service)
    {
        $this->service = $service;
    }
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'salary_min', 'salary_max']);
        $structures = $this->service->getIndexData($filters);
        return view('modules.salary_structure.index', compact('structures'));
    }
    
    public function dashboard()
    {
        $dashboardData = $this->service->getDashboardData();
        return view('modules.salary_structure.dashboard', $dashboardData);
    }

    public function create()
    {
        $users = $this->service->getUsers();
        return view('modules.salary_structure.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'da' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
        ]);

        $this->service->store($request->all());

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
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'da' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
        ]);

        $this->service->update($request->id, $request->only([
            'basic_salary', 'hra', 'da', 'other_allowance'
        ]));

        return redirect()->route('dashboard_salary.index')
                         ->with('success', 'Salary structure updated successfully.');
    }

    public function delete(Request $req)
    {
        $this->service->delete($req->id);

        return redirect()->route('dashboard_salary.index')
                         ->with('success', 'Salary structure deleted.');
    }
}
