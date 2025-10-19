<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface BaseControllerInterface
{
    // public function index();
    // public function show(int $id);
    // public function store(Request $request);
    // public function update(Request $request, int $id);
    // public function destroy(int $id);

    public function getIndexView(Request $request);
    public function getCreateView(Request $request);
    public function getEditView(Request $request);
    public function getDetailView(Request $request);
    public function submitCreateForm(Request $request);
    public function submitUpdateForm(Request $request);
    public function submitDeleteForm(Request $request);
    public function getIndexData(Request $request);
    public function getDetailData(Request $request);
    
}
