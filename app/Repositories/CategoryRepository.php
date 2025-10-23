<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements BaseRepositoryInterface
{
    protected Category $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'])
    {
        return $this->model->where('deleted', 0)->get($columns);
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
        return $this->model->paginate($perPage, $columns);
    }

    public function search(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->newQuery()->where('deleted', 0);
        
        if (!empty($filters['name'])) {
            $query->where('name', 'LIKE', "%{$filters['name']}%");
        }
        
        if (!empty($filters['code'])) {
            $query->where('code', 'LIKE', "%{$filters['code']}%");
        }
        
        if (!empty($filters['description'])) {
            $query->where('description', 'LIKE', "%{$filters['description']}%");
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
        $query = $this->model->newQuery()->where('deleted', 0);
        
        if (!empty($filters['name'])) {
            $query->where('name', 'LIKE', "%{$filters['name']}%");
        }
        
        if (!empty($filters['code'])) {
            $query->where('code', 'LIKE', "%{$filters['code']}%");
        }
        
        return $query->orderBy('created_at', 'desc')->get();
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