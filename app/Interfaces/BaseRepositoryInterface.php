<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*']);
    public function find(int $id, array $columns = ['*']);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function paginate(int $perPage = 10, array $columns = ['*']);
    public function search(array $filters = [], int $perPage = 10);

    // Extended for UI and Data handling
    public function getIndexData(array $filters = []);
    public function getDetailData(int $id);
}
