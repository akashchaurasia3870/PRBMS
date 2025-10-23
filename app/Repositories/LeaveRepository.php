<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Models\Leave;

class LeaveRepository implements BaseRepositoryInterface
{
    protected Leave $model;

    public function __construct(Leave $model)
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
        $query = $this->model->newQuery()->with(['user', 'approvedBy', 'rejectedBy'])
            ->where('deleted', '!=', 1);

        if (!empty($filters['user'])) {
            $query->whereHas('user', function($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['user']}%")
                  ->orWhere('email', 'LIKE', "%{$filters['user']}%");
            });
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['leave_type'])) {
            $query->where('leave_type', $filters['leave_type']);
        }
        
        if (!empty($filters['from_date'])) {
            $query->whereDate('start_date', '>=', $filters['from_date']);
        }
        
        if (!empty($filters['to_date'])) {
            $query->whereDate('end_date', '<=', $filters['to_date']);
        }
        
        if (!empty($filters['days_min'])) {
            $query->where('days_count', '>=', $filters['days_min']);
        }
        
        if (!empty($filters['days_max'])) {
            $query->where('days_count', '<=', $filters['days_max']);
        }
        
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getIndexData(array $filters = [])
    {
        return $this->search($filters, 10);
    }

    public function getDetailData(int $id)
    {
        return $this->model->with(['user', 'approvedBy', 'rejectedBy'])
            ->where('deleted', '!=', 1)
            ->findOrFail($id);
    }

    public function getAllForExport(array $filters = [])
    {
        $query = $this->model->newQuery()->with(['user', 'approvedBy', 'rejectedBy'])
            ->where('deleted', '!=', 1);

        if (!empty($filters['user'])) {
            $query->whereHas('user', function($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['user']}%")
                  ->orWhere('email', 'LIKE', "%{$filters['user']}%");
            });
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['leave_type'])) {
            $query->where('leave_type', $filters['leave_type']);
        }
        
        if (!empty($filters['from_date'])) {
            $query->whereDate('start_date', '>=', $filters['from_date']);
        }
        
        if (!empty($filters['to_date'])) {
            $query->whereDate('end_date', '<=', $filters['to_date']);
        }
        
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
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

    public function approveLeave(int $id)
    {
        return $this->update($id, [
            'status' => 'approved',
            'approved_by' => auth()->id()
        ]);
    }

    public function rejectLeave(int $id, string $reason = null)
    {
        $data = [
            'status' => 'rejected',
            'rejected_by' => auth()->id()
        ];
        if ($reason) {
            $data['rejection_reason'] = $reason;
        }
        return $this->update($id, $data);
    }
    
    public function getLeavesByStatus(string $status, int $limit = null)
    {
        $query = $this->model->with(['user', 'approvedBy', 'rejectedBy'])
                    ->where('deleted', '!=', 1)
                    ->where('status', $status)
                    ->orderBy('created_at', 'desc');
                    
        return $limit ? $query->limit($limit)->get() : $query->get();
    }
    
    public function getLeavesByUser(int $userId, array $filters = [])
    {
        $query = $this->model->with(['user', 'approvedBy', 'rejectedBy'])
                    ->where('deleted', '!=', 1)
                    ->where('user_id', $userId);
                    
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['leave_type'])) {
            $query->where('leave_type', $filters['leave_type']);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
    
    public function getUpcomingLeaves(int $days = 30)
    {
        return $this->model->with(['user'])
                    ->where('deleted', '!=', 1)
                    ->where('status', 'approved')
                    ->where('start_date', '>', now())
                    ->where('start_date', '<=', now()->addDays($days))
                    ->orderBy('start_date', 'asc')
                    ->get();
    }
    
    public function getActiveLeaves()
    {
        return $this->model->with(['user'])
                    ->where('deleted', '!=', 1)
                    ->where('status', 'approved')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->orderBy('start_date', 'asc')
                    ->get();
    }
    
    public function getLeaveStatistics(array $filters = [])
    {
        $query = $this->model->where('deleted', '!=', 1);
        
        if (!empty($filters['from_date'])) {
            $query->whereDate('start_date', '>=', $filters['from_date']);
        }
        
        if (!empty($filters['to_date'])) {
            $query->whereDate('end_date', '<=', $filters['to_date']);
        }
        
        $leaves = $query->get();
        
        return [
            'total' => $leaves->count(),
            'pending' => $leaves->where('status', 'pending')->count(),
            'approved' => $leaves->where('status', 'approved')->count(),
            'rejected' => $leaves->where('status', 'rejected')->count(),
            'by_type' => $leaves->groupBy('leave_type')->map->count(),
            'total_days' => $leaves->where('status', 'approved')->sum('days_count')
        ];
    }
}