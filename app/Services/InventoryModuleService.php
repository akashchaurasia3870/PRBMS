<?php

namespace App\Services;

use App\Models\Inventory;

class InventoryService
{
    
    public function index()
    {
        return Inventory::all();
    }

    public function create()
    {
        // If you want to pass any defaults to create form
        return [];
    }

    public function store(array $data)
    {
        return Inventory::create($data);
    }

    public function show($id)
    {
        return Inventory::findOrFail($id);
    }

    public function edit($id)
    {
        return Inventory::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $client = Inventory::findOrFail($id);
        $client->update($data);
        return $client;
    }

    public function destroy($id)
    {
        $client = Inventory::findOrFail($id);
        return $client->delete();
    }
}
