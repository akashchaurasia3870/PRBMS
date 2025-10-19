<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\ExpenseTracker;
use App\Models\ExpenseType;
use App\Services\ExpenseTrackerService;
use App\Repositories\ExpenseTrackerRepository;
use App\Repositories\ExpenseTypeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ExpenseTrackerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $expenseRepository;
    protected $expenseTypeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->expenseRepository = Mockery::mock(ExpenseTrackerRepository::class);
        $this->expenseTypeRepository = Mockery::mock(ExpenseTypeRepository::class);
        
        $this->service = new ExpenseTrackerService(
            $this->expenseRepository,
            $this->expenseTypeRepository
        );
    }

    /** @test */
    public function it_can_get_all_expenses()
    {
        $expenses = collect([new ExpenseTracker(), new ExpenseTracker()]);
        
        $this->expenseRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($expenses);

        $result = $this->service->getAll();

        $this->assertEquals($expenses, $result);
    }

    /** @test */
    public function it_can_get_expense_by_id()
    {
        $expense = new ExpenseTracker();
        $expense->id = 1;
        
        $this->expenseRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($expense);

        $result = $this->service->getById(1);

        $this->assertEquals($expense, $result);
    }

    /** @test */
    public function it_can_create_expense()
    {
        $data = [
            'type' => 'Office Supplies',
            'amount' => 150.50,
            'description' => 'Test expense',
            'expense_date' => '2024-01-15'
        ];

        $expense = new ExpenseTracker();
        
        $this->expenseRepository
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($expense);

        $result = $this->service->store($data);

        $this->assertEquals($expense, $result);
    }

    /** @test */
    public function it_can_update_expense()
    {
        $data = [
            'type' => 'Updated Type',
            'amount' => 200.00,
            'description' => 'Updated description'
        ];

        $expense = new ExpenseTracker();
        
        $this->expenseRepository
            ->shouldReceive('update')
            ->with(1, $data)
            ->once()
            ->andReturn($expense);

        $result = $this->service->update(1, $data);

        $this->assertEquals($expense, $result);
    }

    /** @test */
    public function it_can_delete_expense()
    {
        $expense = new ExpenseTracker();
        
        $this->expenseRepository
            ->shouldReceive('delete')
            ->with(1)
            ->once()
            ->andReturn($expense);

        $result = $this->service->destroy(1);

        $this->assertEquals($expense, $result);
    }

    /** @test */
    public function it_can_search_expenses_with_filters()
    {
        $filters = ['type' => 'Travel'];
        $expenses = collect([new ExpenseTracker()]);
        
        $this->expenseRepository
            ->shouldReceive('search')
            ->with($filters, 10)
            ->once()
            ->andReturn($expenses);

        $result = $this->service->search($filters);

        $this->assertEquals($expenses, $result);
    }

    /** @test */
    public function it_can_get_dashboard_data()
    {
        $filters = [];
        $totalExpenses = 1000.00;
        $expensesByType = collect([]);
        $recentExpenses = collect([]);
        
        $this->expenseRepository
            ->shouldReceive('getTotalExpenses')
            ->with($filters)
            ->once()
            ->andReturn($totalExpenses);
            
        $this->expenseRepository
            ->shouldReceive('getExpensesByType')
            ->with($filters)
            ->once()
            ->andReturn($expensesByType);
            
        $this->expenseRepository
            ->shouldReceive('search')
            ->with($filters, 5)
            ->once()
            ->andReturn($recentExpenses);
            
        $mockPaginator = \Mockery::mock('\Illuminate\Pagination\LengthAwarePaginator');
        $mockPaginator->shouldReceive('total')->andReturn(10);
        
        $this->expenseRepository
            ->shouldReceive('search')
            ->with($filters, 1)
            ->once()
            ->andReturn($mockPaginator);

        $result = $this->service->getDashboardData($filters);

        $this->assertArrayHasKey('total_expenses', $result);
        $this->assertArrayHasKey('expenses_by_type', $result);
        $this->assertArrayHasKey('recent_expenses', $result);
        $this->assertArrayHasKey('expense_count', $result);
    }

    /** @test */
    public function it_can_get_expense_types()
    {
        $expenseTypes = collect([new ExpenseType()]);
        
        $this->expenseTypeRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($expenseTypes);

        $result = $this->service->getExpenseTypes();

        $this->assertEquals($expenseTypes, $result);
    }

    /** @test */
    public function it_can_bulk_delete_expenses()
    {
        $ids = [1, 2, 3];
        
        $this->expenseRepository
            ->shouldReceive('bulkDelete')
            ->with($ids)
            ->once()
            ->andReturn(true);

        $result = $this->service->bulkDelete($ids);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}