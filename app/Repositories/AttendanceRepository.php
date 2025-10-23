<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceRepository implements BaseRepositoryInterface
{
    protected Attendance $model;

    public function __construct(Attendance $model)
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
        $query = $this->model->newQuery()->where('deleted', '!=', 1)->with('user');
        
        if (!empty($filters['user_name'])) {
            $query->whereHas('user', function($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['user_name']}%");
            });
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }
        
        return $query->orderBy('date', 'desc')->paginate($perPage);
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
        $query = $this->model->newQuery()->where('deleted', '!=', 1)->with('user');
        
        if (!empty($filters['user_name'])) {
            $query->whereHas('user', function($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['user_name']}%");
            });
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }
        
        return $query->orderBy('date', 'desc')->get();
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

    public function getAttendanceSummary(array $filters = [])
    {
        $month = $filters['month'] ?? date('m');
        $year = $filters['year'] ?? date('Y');
        $search = $filters['search'] ?? null;

        $query = DB::table('attendance_view')
            ->selectRaw('id, name, month, total_days as totalWorkingDays, present_count as presentDays, (total_days - present_count) as absentDays')
            ->where('month', $month)
            ->where('year', $year);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('id')->paginate(10);
    }

    public function markAttendance(array $data)
    {
        $attendance = $this->model->where('user_id', $data['user_id'])
            ->where('date', $data['date'])
            ->first();

        if ($attendance) {
            $attendance->update($data);
            return $attendance;
        } else {
            return $this->model->create($data);
        }
    }

    public function markAllAttendance(array $data)
    {
        $users = \App\Models\User::where('deleted', 0)->get();
        $results = [];
        
        foreach ($users as $user) {
            $attendanceData = array_merge($data, ['user_id' => $user->id]);
            $results[] = $this->markAttendance($attendanceData);
        }
        
        return $results;
    }
}