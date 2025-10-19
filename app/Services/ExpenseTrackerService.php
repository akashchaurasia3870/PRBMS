<?php

namespace App\Services;

use App\Interfaces\BaseServiceInterface;
use App\Repositories\ExpenseTrackerRepository;
use App\Repositories\ExpenseTypeRepository;

class ExpenseTrackerService implements BaseServiceInterface
{
    protected ExpenseTrackerRepository $repository;
    protected ExpenseTypeRepository $expense_type_repository;

    public function __construct(ExpenseTrackerRepository $repository,ExpenseTypeRepository $expense_type_repository)
    {
        $this->repository = $repository;
        $this->expense_type_repository = $expense_type_repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById(int $id)
    {
        return $this->repository->find($id);
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy(int $id)
    {
        return $this->repository->delete($id);
    }

    public function paginate(int $perPage = 10)
    {
        return $this->repository->paginate($perPage);
    }

    public function search(array $filters = [], int $perPage = 10)
    {
        return $this->repository->search($filters, $perPage);
    }

    // UI-based operations
    public function getIndexView(array $params = [])
    {
        return $this->getIndexData($params);
    }

    public function getCreateView(array $params = [])
    {
        return $this->expense_type_repository->all()->where('deleted', 0);
    }

    public function getEditView(int $id)
    {
        return $this->repository->find($id);
    }

    public function getDetailView(int $id)
    {
        return $this->repository->find($id);
    }

    public function submitCreateForm(array $data)
    {
        return $this->repository->create($data);
    }

    public function submitUpdateForm(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function submitDeleteForm(int $id)
    {
        return $this->repository->delete($id);
    }

    public function getIndexData(array $filters = [])
    {
        return $this->repository->getIndexData($filters);
    }

    public function getDetailData(int $id)
    {
        return $this->repository->getDetailData($id);
    }
    
    public function getDashboardData(array $filters = [])
    {
        $totalExpenses = $this->repository->getTotalExpenses($filters);
        $expensesByType = $this->repository->getExpensesByType($filters);
        $recentExpenses = $this->repository->search($filters, 5);
        
        return [
            'total_expenses' => $totalExpenses,
            'expenses_by_type' => $expensesByType,
            'recent_expenses' => $recentExpenses,
            'expense_count' => $this->repository->search($filters, 1)->total()
        ];
    }
    
    public function getMonthlyExpenses()
    {
        return $this->repository->getMonthlyExpenses();
    }
    
    public function getAllForExport(array $filters = [])
    {
        return $this->repository->getAllForExport($filters);
    }
    
    public function getByIds(array $ids)
    {
        return $this->repository->getByIds($ids);
    }
    
    public function bulkDelete(array $ids)
    {
        return $this->repository->bulkDelete($ids);
    }
    
    public function getExpenseTypes()
    {
        return $this->expense_type_repository->all();
    }
    

}