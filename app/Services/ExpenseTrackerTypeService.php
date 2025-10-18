<?php

namespace App\Services;

use App\Interfaces\BaseServiceInterface;
use App\Repositories\ExpenseTypeRepository;

class ExpenseTrackerTypeService implements BaseServiceInterface
{
    protected ExpenseTypeRepository $repository;

    public function __construct(ExpenseTypeRepository $repository)
    {
        $this->repository = $repository;
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
        return $params;
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
    // public function getAllExpenses($filters = [])
    // {
    //     $query = ExpenseType::query();

    //     // if (!empty($filters['expense_date'])) {
    //     //     $query->whereDate('expense_date', $filters['expense_date']);
    //     // }

    //     // if (!empty($filters['type'])) {
    //     //     $query->where('type', 'like', "%{$filters['type']}%");
    //     // }

    //     // if (!empty($filters['description'])) {
    //     //     $query->where('description', 'like', "%{$filters['description']}%");
    //     // }

    //     $expenseTypes =  $query->where('delete', 0)->paginate(10)->appends(request()->except('page'));
    //     return compact('expenseTypes');
    // }

    // public function createExpense($data)
    // {
    //     return ExpenseType::create($data);
    // }

    // public function updateExpense($id, $data)
    // {
    //     $expense = ExpenseType::findOrFail($id);
    //     $expense->update($data);
    //     return $expense;
    // }

    // public function deleteExpense($id, $userId)
    // {
    //     $expense = ExpenseType::findOrFail($id);
    //     $expense->delete = 1;
    //     $expense->deleted_by = $userId;
    //     $expense->save();
    //     return $expense;
    // }

    // public function getExpenseTypes()
    // {
    //     return ExpenseType::where('delete', 0)->get();
    // }
}
