<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ExpenseTracker;
use App\Models\ExpenseType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ExpenseTrackerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_expense_index_page()
    {
        $this->actingAs($this->user)
            ->get(route('expense.v1.index'))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_tracker.index');
    }

    /** @test */
    public function user_can_view_expense_create_page()
    {
        $this->actingAs($this->user)
            ->get(route('expense.v1.new'))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_tracker.new');
    }

    /** @test */
    public function user_can_create_expense()
    {
        $expenseData = [
            'type' => 'Office Supplies',
            'amount' => 150.50,
            'description' => 'Purchased office supplies for the team',
            'expense_date' => '2024-01-15'
        ];

        $this->actingAs($this->user)
            ->post(route('expense.v2.new'), $expenseData)
            ->assertRedirect(route('expense.v1.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expenses', [
            'type' => 'Office Supplies',
            'amount' => 150.50,
            'description' => 'Purchased office supplies for the team',
            'created_by' => $this->user->id
        ]);
    }

    /** @test */
    public function expense_creation_requires_valid_data()
    {
        $this->actingAs($this->user)
            ->post(route('expense.v2.new'), [])
            ->assertSessionHasErrors(['type', 'amount', 'description', 'expense_date']);
    }

    /** @test */
    public function user_can_view_expense_details()
    {
        $expense = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('expense.v1.show', $expense->id))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_tracker.show');
    }

    /** @test */
    public function user_can_view_expense_edit_page()
    {
        $expense = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('expense.v1.edit', $expense->id))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_tracker.edit');
    }

    /** @test */
    public function user_can_update_expense()
    {
        $expense = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $updateData = [
            'type' => 'Updated Type',
            'amount' => 200.00,
            'description' => 'Updated description',
            'expense_date' => '2024-01-20'
        ];

        $this->actingAs($this->user)
            ->post(route('expense.v2.edit', $expense->id), $updateData)
            ->assertRedirect(route('expense.v1.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'type' => 'Updated Type',
            'amount' => 200.00,
            'updated_by' => $this->user->id
        ]);
    }

    /** @test */
    public function user_can_delete_expense()
    {
        $expense = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->post(route('expense.v2.delete', $expense->id))
            ->assertRedirect(route('expense.v1.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'deleted' => 1,
            'deleted_by' => $this->user->id
        ]);
    }

    /** @test */
    public function user_can_filter_expenses_by_type()
    {
        ExpenseTracker::factory()->create(['type' => 'Travel', 'created_by' => $this->user->id]);
        ExpenseTracker::factory()->create(['type' => 'Food', 'created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('expense.v1.index', ['type' => 'Travel']))
            ->assertStatus(200)
            ->assertSee('Travel')
            ->assertDontSee('Food');
    }

    /** @test */
    public function user_can_filter_expenses_by_date_range()
    {
        ExpenseTracker::factory()->create([
            'expense_date' => '2024-01-15',
            'created_by' => $this->user->id
        ]);
        ExpenseTracker::factory()->create([
            'expense_date' => '2024-02-15',
            'created_by' => $this->user->id
        ]);

        $this->actingAs($this->user)
            ->get(route('expense.v1.index', [
                'date_from' => '2024-01-01',
                'date_to' => '2024-01-31'
            ]))
            ->assertStatus(200);
    }

    /** @test */
    public function user_can_export_expenses_to_csv()
    {
        ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('expense.v1.index', ['export' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    /** @test */
    public function user_can_bulk_delete_expenses()
    {
        $expense1 = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);
        $expense2 = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('expense.v1.index', [
                'bulk_delete' => $expense1->id . ',' . $expense2->id
            ]))
            ->assertRedirect(route('expense.v1.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expenses', ['id' => $expense1->id, 'deleted' => 1]);
        $this->assertDatabaseHas('expenses', ['id' => $expense2->id, 'deleted' => 1]);
    }

    /** @test */
    public function user_can_view_dashboard()
    {
        ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('expense.v1.dashboard'))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_tracker.dashboard');
    }

    /** @test */
    public function user_can_view_audit_logs()
    {
        $this->actingAs($this->user)
            ->get(route('expense.audit.logs'))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_tracker.audit_logs');
    }

    /** @test */
    public function expense_amount_validation()
    {
        $this->actingAs($this->user)
            ->post(route('expense.v2.new'), [
                'type' => 'Test',
                'amount' => -50,
                'description' => 'Test',
                'expense_date' => '2024-01-15'
            ])
            ->assertSessionHasErrors(['amount']);
    }

    /** @test */
    public function expense_date_validation()
    {
        $this->actingAs($this->user)
            ->post(route('expense.v2.new'), [
                'type' => 'Test',
                'amount' => 50,
                'description' => 'Test',
                'expense_date' => 'invalid-date'
            ])
            ->assertSessionHasErrors(['expense_date']);
    }
}