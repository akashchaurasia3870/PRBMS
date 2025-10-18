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
        return $this->find($id)->delete();
    }

    public function paginate(int $perPage = 10, array $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function search(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->newQuery();
        foreach ($filters as $key => $value) {
            if ($value !== null) {
                $query->where($key, 'LIKE', "%$value%");
            }
        }
        return $query->paginate($perPage);
    }

    public function getIndexData(array $filters = [])
    {
        return $this->search($filters);
    }

    public function getDetailData(int $id)
    {
        return $this->find($id);
    }
}
