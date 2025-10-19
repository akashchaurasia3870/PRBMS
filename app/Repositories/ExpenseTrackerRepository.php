<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Models\ExpenseTracker;


class ExpenseTrackerRepository implements BaseRepositoryInterface
{
    protected ExpenseTracker $model;

    public function __construct(ExpenseTracker $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'])
    {
        return $this->model->all($columns);
    }

    public function find(int $id, array $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete(int $id)
    {
        $record = $this->find($id);
        $record->update([
            'deleted' => 1,
            'deleted_by' => auth()->id()
        ]);
        $record->delete(); // This sets deleted_at via SoftDeletes trait
        return $record->fresh();
    }

    public function paginate(int $perPage = 10, array $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function search(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->newQuery()->where('deleted', 0);
        
        if (!empty($filters['type'])) {
            $query->where('type', 'LIKE', "%{$filters['type']}%");
        }
        
        if (!empty($filters['description'])) {
            $query->where('description', 'LIKE', "%{$filters['description']}%");
        }
        
        if (!empty($filters['expense_date'])) {
            $query->whereDate('expense_date', $filters['expense_date']);
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('expense_date', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('expense_date', '<=', $filters['date_to']);
        }
        
        if (!empty($filters['amount_min'])) {
            $query->where('amount', '>=', $filters['amount_min']);
        }
        
        if (!empty($filters['amount_max'])) {
            $query->where('amount', '<=', $filters['amount_max']);
        }
        
        return $query->orderBy('expense_date', 'desc')->paginate($perPage);
    }

    public function getIndexData(array $filters = [])
    {
        return $this->search($filters, 10);
    }
    
    public function getTotalExpenses(array $filters = [])
    {
        $query = $this->model->newQuery()->where('deleted', 0);
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('expense_date', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('expense_date', '<=', $filters['date_to']);
        }
        
        return $query->sum('amount');
    }
    
    public function getExpensesByType(array $filters = [])
    {
        $query = $this->model->newQuery()->where('deleted', 0);
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('expense_date', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('expense_date', '<=', $filters['date_to']);
        }
        
        return $query->selectRaw('type, SUM(amount) as total_amount, COUNT(*) as count')
                    ->groupBy('type')
                    ->orderBy('total_amount', 'desc')
                    ->get();
    }
    
    public function getMonthlyExpenses()
    {
        return $this->model->newQuery()
                    ->where('deleted', 0)
                    ->selectRaw('strftime("%Y", expense_date) as year, strftime("%m", expense_date) as month, SUM(amount) as total')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->limit(12)
                    ->get();
    }

    public function getDetailData(int $id)
    {
        return $this->find($id);
    }
    
    public function getAllForExport(array $filters = [])
    {
        $query = $this->model->newQuery()->where('deleted', 0);
        
        if (!empty($filters['type'])) {
            $query->where('type', 'LIKE', "%{$filters['type']}%");
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('expense_date', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('expense_date', '<=', $filters['date_to']);
        }
        
        return $query->orderBy('expense_date', 'desc')->get();
    }
    
    public function getByIds(array $ids)
    {
        return $this->model->whereIn('id', $ids)->where('deleted', 0)->get();
    }
    
    public function bulkDelete(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update([
            'deleted' => 1,
            'deleted_by' => auth()->id(),
            'deleted_at' => now()
        ]);
    }
}