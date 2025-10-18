<?php

namespace App\Interfaces;

interface BaseServiceInterface
{
    public function getAll();
    public function getById(int $id);
    public function store(array $data);
    public function update(int $id, array $data);
    public function destroy(int $id);
    public function paginate(int $perPage = 10);
    public function search(array $filters = [], int $perPage = 10);

    // Extended for view/data logic
    public function getIndexView(array $params = []);
    public function getCreateView(array $params = []);
    public function getEditView(int $id);
    public function getDetailView(int $id);
    public function submitCreateForm(array $data);
    public function submitUpdateForm(int $id, array $data);
    public function submitDeleteForm(int $id);
    public function getIndexData(array $filters = []);
    public function getDetailData(int $id);
}
