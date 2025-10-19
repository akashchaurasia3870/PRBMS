<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ExpenseTracker;
use App\Models\ExpenseType;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseModuleIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function complete_expense_workflow()
    {
        // 1. Create expense type
        $expenseTypeData = [
            'type' => 'Travel & Transportation',
            'description' => 'All travel related expenses'
        ];

        $this->actingAs($this->user)
            ->post(route('expense_type.v2.new'), $expenseTypeData)
            ->assertRedirect(route('expense_type.v1.index'))
            ->assertSessionHas('success');

        $expenseType = ExpenseType::where('type', 'Travel & Transportation')->first();
        $this->assertNotNull($expenseType);

        // 2. Create expense using the type
        $expenseData = [
            'type' => 'Travel & Transportation',
            'amount' => 500.00,
            'description' => 'Flight tickets for business trip',
            'expense_date' => '2024-01-15'
        ];

        $this->actingAs($this->user)
            ->post(route('expense.v2.new'), $expenseData)
            ->assertRedirect(route('expense.v1.index'))
            ->assertSessionHas('success');

        $expense = ExpenseTracker::where('type', 'Travel & Transportation')->first();
        $this->assertNotNull($expense);

        // 3. View expense details
        $this->actingAs($this->user)
            ->get(route('expense.v1.show', $expense->id))
            ->assertStatus(200)
            ->assertSee('Travel & Transportation')
            ->assertSee('500.00');

        // 4. Update expense
        $updateData = [
            'type' => 'Travel & Transportation',
            'amount' => 600.00,
            'description' => 'Updated: Flight tickets and hotel',
            'expense_date' => '2024-01-15'
        ];

        $this->actingAs($this->user)
            ->post(route('expense.v2.edit', $expense->id), $updateData)
            ->assertRedirect(route('expense.v1.index'))
            ->assertSessionHas('success');

        $expense->refresh();
        $this->assertEquals(600.00, $expense->amount);

        // 5. Check audit logs were created
        $auditLogs = AuditLog::where('auditable_type', 'App\Models\ExpenseTracker')
            ->where('auditable_id', $expense->id)
            ->get();

        $this->assertGreaterThanOrEqual(2, $auditLogs->count()); // Create and update

        // 6. Filter expenses
        $this->actingAs($this->user)
            ->get(route('expense.v1.index', ['type' => 'Travel']))
            ->assertStatus(200)
            ->assertSee('Travel & Transportation');

        // 7. Export expenses
        $response = $this->actingAs($this->user)
            ->get(route('expense.v1.index', ['export' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        // 8. Delete expense
        $this->actingAs($this->user)
            ->post(route('expense.v2.delete', $expense->id))
            ->assertRedirect(route('expense.v1.index'))
            ->assertSessionHas('success');

        $expense->refresh();
        $this->assertEquals(1, $expense->deleted);
    }

    /** @test */
    public function dashboard_displays_correct_statistics()
    {
        // Create test data
        ExpenseTracker::factory()->create([
            'type' => 'Travel',
            'amount' => 500,
            'created_by' => $this->user->id
        ]);
        ExpenseTracker::factory()->create([
            'type' => 'Food',
            'amount' => 100,
            'created_by' => $this->user->id
        ]);
        ExpenseTracker::factory()->create([
            'type' => 'Travel',
            'amount' => 300,
            'created_by' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('expense.v1.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('data');
        
        $data = $response->viewData('data');
        $this->assertEquals(900, $data['total_expenses']);
        $this->assertEquals(3, $data['expense_count']);
    }

    /** @test */
    public function bulk_operations_work_correctly()
    {
        $expense1 = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);
        $expense2 = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);
        $expense3 = ExpenseTracker::factory()->create(['created_by' => $this->user->id]);

        // Test bulk delete
        $this->actingAs($this->user)
            ->get(route('expense.v1.index', [
                'bulk_delete' => $expense1->id . ',' . $expense2->id
            ]))
            ->assertRedirect(route('expense.v1.index'))
            ->assertSessionHas('success');

        $expense1->refresh();
        $expense2->refresh();
        $expense3->refresh();

        $this->assertEquals(1, $expense1->deleted);
        $this->assertEquals(1, $expense2->deleted);
        $this->assertEquals(0, $expense3->deleted);

        // Test bulk export
        $response = $this->actingAs($this->user)
            ->get(route('expense.v1.index', [
                'export_selected' => $expense3->id
            ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    /** @test */
    public function filtering_works_with_multiple_criteria()
    {
        ExpenseTracker::factory()->create([
            'type' => 'Travel',
            'amount' => 500,
            'expense_date' => '2024-01-15',
            'created_by' => $this->user->id
        ]);
        ExpenseTracker::factory()->create([
            'type' => 'Food',
            'amount' => 100,
            'expense_date' => '2024-01-20',
            'created_by' => $this->user->id
        ]);
        ExpenseTracker::factory()->create([
            'type' => 'Travel',
            'amount' => 200,
            'expense_date' => '2024-02-15',
            'created_by' => $this->user->id
        ]);

        // Filter by type and date range
        $response = $this->actingAs($this->user)
            ->get(route('expense.v1.index', [
                'type' => 'Travel',
                'date_from' => '2024-01-01',
                'date_to' => '2024-01-31'
            ]));

        $response->assertStatus(200);
        // Should only show the first Travel expense from January
    }

    /** @test */
    public function audit_logs_are_created_for_all_operations()
    {
        // Create expense type via controller to trigger audit log
        $expenseTypeData = [
            'type' => 'Test Audit Type',
            'description' => 'Test audit description'
        ];

        $this->actingAs($this->user)
            ->post(route('expense_type.v2.new'), $expenseTypeData);

        $expenseType = ExpenseType::where('type', 'Test Audit Type')->first();

        // Create expense via controller to trigger audit log
        $expenseData = [
            'type' => 'Test Audit Expense',
            'amount' => 100.00,
            'description' => 'Test audit expense',
            'expense_date' => '2024-01-15'
        ];

        $this->actingAs($this->user)
            ->post(route('expense.v2.new'), $expenseData);

        $expense = ExpenseTracker::where('type', 'Test Audit Expense')->first();

        // Check audit logs exist
        $expenseTypeLog = AuditLog::where('auditable_type', 'App\Models\ExpenseType')
            ->where('auditable_id', $expenseType->id)
            ->where('action', 'created')
            ->first();

        $expenseLog = AuditLog::where('auditable_type', 'App\Models\ExpenseTracker')
            ->where('auditable_id', $expense->id)
            ->where('action', 'created')
            ->first();

        $this->assertNotNull($expenseTypeLog);
        $this->assertNotNull($expenseLog);
    }
}