<?php

namespace App\Http\Controllers;

use App\Services\ExpenseTrackerService;
use App\Interfaces\BaseControllerInterface;
use Illuminate\Http\Request;

class ExpenseTrackerController extends Controller implements BaseControllerInterface
{
    protected ExpenseTrackerService $service;

    public function __construct(ExpenseTrackerService $service)
    {
        $this->service = $service;
    }

    public function getIndexView(Request $request)
    {
        $data = $this->service->getIndexView($request->all());
        return view('modules.expense_tracker.index', compact('data'));
    }

    public function getCreateView(Request $request)
    {
        $data = $this->service->getCreateView($request->all());
        
        return view('modules.expense_tracker.new', compact('data'));
    }

    public function getEditView(Request $request)
    {
        $data = $this->service->getEditView($request->route('id'));
        return view('modules.expense_tracker.edit', compact('data'));
    }

    public function getDetailView(Request $request)
    {
        $data = $this->service->getDetailView($request->route('id'));
        return view('modules.expense_tracker.show', compact('data'));
    }

    public function submitCreateForm(Request $request)
    {
        $this->service->submitCreateForm($request->all());
        return redirect()->back()->with('success', 'Created successfully.');
    }

    public function submitUpdateForm(Request $request)
    {
        $this->service->submitUpdateForm($request->route('id'), $request->all());
        return redirect()->back()->with('success', 'Updated successfully.');
    }

    public function submitDeleteForm(Request $request)
    {
        $this->service->submitDeleteForm($request->route('id'));
        return redirect()->back()->with('success', 'Deleted successfully.');
    }

    public function getIndexData(Request $request)
    {
        return response()->json($this->service->getIndexData($request->all()));
    }

    public function getDetailData(Request $request)
    {
        return response()->json($this->service->getDetailData($request->id));
    }
}
