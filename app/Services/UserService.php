<?php

namespace App\Services;

use App\Interfaces\BaseServiceInterface;
use App\Repositories\UserRepository;

class UserService implements BaseServiceInterface
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
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
}