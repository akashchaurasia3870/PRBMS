<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Models\Inventory;

class InventoryRepository implements BaseRepositoryInterface
{
    protected Inventory $model;

    public function __construct(Inventory $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'])
    {
        return $this->model->with('category')->get($columns);
    }

    public function find(int $id, array $columns = ['*'])
    {
        return $this->model->with('category')->findOrFail($id, $columns);
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
            'deleted_by' => auth()->id()
        ]);
        $record->delete();
        return $record->fresh();
    }

    public function paginate(int $perPage = 10, array $columns = ['*'])
    {
        return $this->model->with('category')->paginate($perPage, $columns);
    }

    public function search(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->newQuery()->with('category');
        
        if (!empty($filters['item_code'])) {
            $query->where('item_code', 'LIKE', "%{$filters['item_code']}%");
        }
        
        if (!empty($filters['item_name'])) {
            $query->where('item_name', 'LIKE', "%{$filters['item_name']}%");
        }
        
        if (!empty($filters['category'])) {
            $query->whereHas('category', function($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['category']}%");
            });
        }
        
        if (!empty($filters['price_min'])) {
            $query->where('item_price', '>=', $filters['price_min']);
        }
        
        if (!empty($filters['price_max'])) {
            $query->where('item_price', '<=', $filters['price_max']);
        }
        
        if (!empty($filters['qty_min'])) {
            $query->where('item_qty', '>=', $filters['qty_min']);
        }
        
        if (!empty($filters['qty_max'])) {
            $query->where('item_qty', '<=', $filters['qty_max']);
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
        $query = $this->model->newQuery()->with('category');
        
        if (!empty($filters['item_code'])) {
            $query->where('item_code', 'LIKE', "%{$filters['item_code']}%");
        }
        
        if (!empty($filters['item_name'])) {
            $query->where('item_name', 'LIKE', "%{$filters['item_name']}%");
        }
        
        if (!empty($filters['category'])) {
            $query->whereHas('category', function($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['category']}%");
            });
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
    
    public function getByIds(array $ids)
    {
        return $this->model->with('category')->whereIn('id', $ids)->get();
    }
    
    public function bulkDelete(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => now()
        ]);
    }
    
    public function getTotalValue(array $filters = [])
    {
        $query = $this->model->newQuery();
        
        if (!empty($filters['category'])) {
            $query->whereHas('category', function($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['category']}%");
            });
        }
        
        return $query->get()->sum(function($item) {
            return ($item->item_price ?? 0) * ($item->item_qty ?? 0);
        });
    }
    
    public function getInventoryByCategory(array $filters = [])
    {
        return $this->model->with('category')
                    ->get()
                    ->groupBy('category_id')
                    ->map(function($items) {
                        $category = $items->first()->category;
                        return (object) [
                            'category_id' => $category->id,
                            'category_name' => $category->name,
                            'item_count' => $items->count(),
                            'total_qty' => $items->sum('item_qty'),
                            'total_value' => $items->sum(function($item) {
                                return ($item->item_price ?? 0) * ($item->item_qty ?? 0);
                            })
                        ];
                    })
                    ->sortByDesc('total_value')
                    ->values();
    }
    
    public function getLowStockItems($threshold = 10)
    {
        return $this->model->with('category')
                    ->where('item_qty', '<=', $threshold)
                    ->orderBy('item_qty', 'asc')
                    ->get();
    }
}