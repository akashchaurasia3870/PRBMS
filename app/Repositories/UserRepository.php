<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Models\User;

class UserRepository implements BaseRepositoryInterface
{
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'])
    {
        return $this->model->where('deleted', '!=', 1)->get($columns);
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
            'deleted_by' => auth()->id(),
            'deleted_at' => now()
        ]);
        return $record;
    }

    public function paginate(int $perPage = 10, array $columns = ['*'])
    {
        return $this->model->where('deleted', '!=', 1)->paginate($perPage, $columns);
    }

    public function search(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->newQuery()->where('deleted', '!=', 1);
        
        if (!empty($filters['name'])) {
            $query->where('name', 'LIKE', "%{$filters['name']}%");
        }
        
        if (!empty($filters['email'])) {
            $query->where('email', 'LIKE', "%{$filters['email']}%");
        }
        
        if (!empty($filters['created_date'])) {
            $query->whereDate('created_at', $filters['created_date']);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getIndexData(array $filters = [])
    {
        return $this->search($filters, 10);
    }

    public function getDetailData(int $id)
    {
        return $this->find($id);
    }
    
    public function getAllForExport(array $filters = [])
    {
        $query = $this->model->newQuery()->where('deleted', '!=', 1);
        
        if (!empty($filters['name'])) {
            $query->where('name', 'LIKE', "%{$filters['name']}%");
        }
        
        if (!empty($filters['email'])) {
            $query->where('email', 'LIKE', "%{$filters['email']}%");
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
    
    public function getByIds(array $ids)
    {
        return $this->model->whereIn('id', $ids)->where('deleted', '!=', 1)->get();
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