<?php

namespace App\Services;

use App\Models\SalaryStructure;
use App\Models\User;

class SalaryStructureService
{
    public function getIndexData(array $filters = [])
    {
        $query = SalaryStructure::with('user');
        
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }
        
        if (!empty($filters['salary_min'])) {
            $query->where('basic_salary', '>=', $filters['salary_min']);
        }
        
        if (!empty($filters['salary_max'])) {
            $query->where('basic_salary', '<=', $filters['salary_max']);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
    
    public function getDashboardData()
    {
        $totalStructures = SalaryStructure::count();
        $avgBasicSalary = SalaryStructure::avg('basic_salary');
        $totalSalaryBudget = SalaryStructure::sum('basic_salary');
        $highestSalary = SalaryStructure::max('basic_salary');
        
        $recentStructures = SalaryStructure::with('user')
                                         ->orderBy('created_at', 'desc')
                                         ->limit(5)
                                         ->get();
        
        $salaryRanges = SalaryStructure::selectRaw('
                CASE 
                    WHEN basic_salary < 30000 THEN "Below 30K"
                    WHEN basic_salary BETWEEN 30000 AND 50000 THEN "30K-50K"
                    WHEN basic_salary BETWEEN 50000 AND 75000 THEN "50K-75K"
                    WHEN basic_salary BETWEEN 75000 AND 100000 THEN "75K-100K"
                    ELSE "Above 100K"
                END as salary_range,
                COUNT(*) as count,
                AVG(basic_salary) as avg_salary
            ')
            ->groupBy('salary_range')
            ->get();
        
        return [
            'total_structures' => $totalStructures,
            'avg_basic_salary' => $avgBasicSalary,
            'total_salary_budget' => $totalSalaryBudget,
            'highest_salary' => $highestSalary,
            'recent_structures' => $recentStructures,
            'salary_ranges' => $salaryRanges
        ];
    }
    
    public function getUsers()
    {
        return User::where('deleted', 0)->get();
    }
    
    public function store(array $data)
    {
        $data['created_by'] = auth()->id() ?? 'System';
        return SalaryStructure::create($data);
    }
    
    public function update(int $id, array $data)
    {
        $data['updated_by'] = auth()->id() ?? 'System';
        $structure = SalaryStructure::findOrFail($id);
        $structure->update($data);
        return $structure;
    }
    
    public function delete(int $id)
    {
        $structure = SalaryStructure::findOrFail($id);
        $structure->update(['deleted_by' => auth()->id() ?? 'System']);
        $structure->delete();
        return $structure;
    }
}