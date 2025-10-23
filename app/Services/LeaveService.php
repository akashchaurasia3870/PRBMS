<?php

namespace App\Services;

use App\Interfaces\BaseServiceInterface;
use App\Repositories\LeaveRepository;
use App\Models\User;

class LeaveService implements BaseServiceInterface
{
    protected LeaveRepository $repository;

    public function __construct(LeaveRepository $repository)
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
        return User::where('deleted', 0)->get();
    }

    public function getEditView(int $id)
    {
        return $this->repository->getDetailData($id);
    }

    public function getDetailView(int $id)
    {
        return $this->repository->getDetailData($id);
    }

    public function submitCreateForm(array $data)
    {
        // Calculate days count
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $data['days_count'] = $this->calculateLeaveDays($data['start_date'], $data['end_date']);
        }
        
        // Set default status
        if (empty($data['status'])) {
            $data['status'] = 'pending';
        }
        
        // Set created_by
        $data['created_by'] = auth()->id();
        
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
        $statistics = $this->repository->getLeaveStatistics($filters);
        $recentLeaves = $this->repository->search($filters, 5);
        $pendingLeaves = $this->repository->getLeavesByStatus('pending', 10);
        $upcomingLeaves = $this->repository->getUpcomingLeaves(7);
        $activeLeaves = $this->repository->getActiveLeaves();
        
        return [
            'statistics' => $statistics,
            'recent_leaves' => $recentLeaves,
            'pending_leaves' => $pendingLeaves,
            'upcoming_leaves' => $upcomingLeaves,
            'active_leaves' => $activeLeaves,
            'leave_types' => $this->getLeaveTypes()
        ];
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

    public function getUsers()
    {
        return User::where('deleted', 0)->get();
    }

    public function approveLeave(int $id)
    {
        return $this->repository->approveLeave($id);
    }

    public function rejectLeave(int $id, string $reason = null)
    {
        return $this->repository->rejectLeave($id, $reason);
    }
    
    public function getLeaveTypes()
    {
        return [
            'sick' => 'Sick Leave',
            'vacation' => 'Vacation',
            'personal' => 'Personal Leave',
            'emergency' => 'Emergency Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'bereavement' => 'Bereavement Leave',
            'other' => 'Other'
        ];
    }
    
    public function getLeaveStatuses()
    {
        return [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];
    }
    
    public function getUserLeaves(int $userId, array $filters = [])
    {
        return $this->repository->getLeavesByUser($userId, $filters);
    }
    
    public function getUpcomingLeaves(int $days = 30)
    {
        return $this->repository->getUpcomingLeaves($days);
    }
    
    public function getActiveLeaves()
    {
        return $this->repository->getActiveLeaves();
    }
    
    public function calculateLeaveDays(string $startDate, string $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        
        return $start->diffInDays($end) + 1;
    }
    
    public function validateLeaveRequest(array $data)
    {
        $errors = [];
        
        if (empty($data['start_date']) || empty($data['end_date'])) {
            $errors[] = 'Start date and end date are required';
        }
        
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $startDate = \Carbon\Carbon::parse($data['start_date']);
            $endDate = \Carbon\Carbon::parse($data['end_date']);
            
            if ($startDate->gt($endDate)) {
                $errors[] = 'Start date cannot be after end date';
            }
            
            if ($startDate->lt(now()->startOfDay())) {
                $errors[] = 'Start date cannot be in the past';
            }
        }
        
        return $errors;
    }
}