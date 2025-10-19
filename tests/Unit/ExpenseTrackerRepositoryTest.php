<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\ExpenseTracker;
use App\Repositories\ExpenseTrackerRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseTrackerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->repository = new ExpenseTrackerRepository(new ExpenseTracker());
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_create_expense()
    {
        $data = [
            'type' => 'Office Supplies',
            'amount' => 150.50,
            'description' => 'Test expense',
            'expense_date' => '2024-01-15',
            'created_by' => $this->user->id
        ];

        $expense = $this->repository->create($data);

        $this->assertInstanceOf(ExpenseTracker::class, $expense);
        $this->assertEquals('Office Supplies', $expense->type);
        $this->assertEquals(150.50, $expense->amount);
    }

    /** @test */
    public function it_can_find_expense_by_id()
    {
        $expense = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $found = $this->repository->find($expense->id);

        $this->assertEquals($expense->id, $found->id);
        $this->assertEquals($expense->type, $found->type);
    }

    /** @test */
    public function it_can_update_expense()
    {
        $expense = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $updateData = [
            'type' => 'Updated Type',
            'amount' => 200.00,
            'description' => 'Updated description'
        ];

        $updated = $this->repository->update($expense->id, $updateData);

        $this->assertEquals('Updated Type', $updated->type);
        $this->assertEquals(200.00, $updated->amount);
    }

    /** @test */
    public function it_can_soft_delete_expense()
    {
        $expense = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $deleted = $this->repository->delete($expense->id);

        $this->assertEquals(1, $deleted->deleted);
        $this->assertEquals($this->user->id, $deleted->deleted_by);
        $this->assertNotNull($deleted->deleted_at);
    }

    /** @test */
    public function it_can_search_expenses_by_type()
    {
        ExpenseTracker::factory()->create(['type' => 'Travel', 'created_by' => $this->user->id]);
        ExpenseTracker::factory()->create(['type' => 'Food', 'created_by' => $this->user->id]);

        $results = $this->repository->search(['type' => 'Travel']);

        $this->assertEquals(1, $results->count());
        $this->assertEquals('Travel', $results->first()->type);
    }

    /** @test */
    public function it_can_search_expenses_by_date_range()
    {
        ExpenseTracker::factory()->create([
            'expense_date' => '2024-01-15',
            'created_by' => $this->user->id
        ]);
        ExpenseTracker::factory()->create([
            'expense_date' => '2024-02-15',
            'created_by' => $this->user->id
        ]);

        $results = $this->repository->search([
            'date_from' => '2024-01-01',
            'date_to' => '2024-01-31'
        ]);

        $this->assertEquals(1, $results->count());
    }

    /** @test */
    public function it_can_search_expenses_by_amount_range()
    {
        ExpenseTracker::factory()->create(['amount' => 100, 'created_by' => $this->user->id]);
        ExpenseTracker::factory()->create(['amount' => 200, 'created_by' => $this->user->id]);
        ExpenseTracker::factory()->create(['amount' => 300, 'created_by' => $this->user->id]);

        $results = $this->repository->search([
            'amount_min' => 150,
            'amount_max' => 250
        ]);

        $this->assertEquals(1, $results->count());
        $this->assertEquals(200, $results->first()->amount);
    }

    /** @test */
    public function it_can_get_total_expenses()
    {
        ExpenseTracker::factory()->create(['amount' => 100, 'created_by' => $this->user->id]);
        ExpenseTracker::factory()->create(['amount' => 200, 'created_by' => $this->user->id]);

        $total = $this->repository->getTotalExpenses();

        $this->assertEquals(300, $total);
    }

    /** @test */
    public function it_can_get_expenses_by_type()
    {
        ExpenseTracker::factory()->create(['type' => 'Travel', 'amount' => 100, 'created_by' => $this->user->id]);
        ExpenseTracker::factory()->create(['type' => 'Travel', 'amount' => 200, 'created_by' => $this->user->id]);
        ExpenseTracker::factory()->create(['type' => 'Food', 'amount' => 50, 'created_by' => $this->user->id]);

        $results = $this->repository->getExpensesByType();

        $this->assertEquals(2, $results->count());
        
        $travelExpenses = $results->where('type', 'Travel')->first();
        $this->assertEquals(300, $travelExpenses->total_amount);
        $this->assertEquals(2, $travelExpenses->count);
    }

    /** @test */
    public function it_can_get_monthly_expenses()
    {
        ExpenseTracker::factory()->create([
            'expense_date' => '2024-01-15',
            'amount' => 100,
            'created_by' => $this->user->id
        ]);
        ExpenseTracker::factory()->create([
            'expense_date' => '2024-01-20',
            'amount' => 200,
            'created_by' => $this->user->id
        ]);

        $results = $this->repository->getMonthlyExpenses();

        $this->assertGreaterThan(0, $results->count());
        $januaryExpenses = $results->where('month', 1)->where('year', 2024)->first();
        $this->assertEquals(300, $januaryExpenses->total);
    }

    /** @test */
    public function it_can_bulk_delete_expenses()
    {
        $expense1 = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);
        $expense2 = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $this->repository->bulkDelete([$expense1->id, $expense2->id]);

        $this->assertDatabaseHas('expenses', ['id' => $expense1->id, 'deleted' => 1]);
        $this->assertDatabaseHas('expenses', ['id' => $expense2->id, 'deleted' => 1]);
    }

    /** @test */
    public function it_excludes_deleted_expenses_from_search()
    {
        $expense1 = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);
        $expense2 = ExpenseTracker::factory()->create(['deleted' => 1, 'created_by' => $this->user->id]);

        $results = $this->repository->search();

        $this->assertEquals(1, $results->count());
        $this->assertEquals($expense1->id, $results->first()->id);
    }
}