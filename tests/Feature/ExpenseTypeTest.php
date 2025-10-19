<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ExpenseType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ExpenseTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_expense_type_index_page()
    {
        $this->actingAs($this->user)
            ->get(route('expense_type.v1.index'))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_type.index');
    }

    /** @test */
    public function user_can_view_expense_type_create_page()
    {
        $this->actingAs($this->user)
            ->get(route('expense_type.v1.new'))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_type.new');
    }

    /** @test */
    public function user_can_create_expense_type()
    {
        $expenseTypeData = [
            'type' => 'Travel & Transportation',
            'description' => 'All travel related expenses including flights, hotels, and transportation'
        ];

        $this->actingAs($this->user)
            ->post(route('expense_type.v2.new'), $expenseTypeData)
            ->assertRedirect(route('expense_type.v1.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expense_type', [
            'type' => 'Travel & Transportation',
            'description' => 'All travel related expenses including flights, hotels, and transportation',
            'created_by' => $this->user->id
        ]);
    }

    /** @test */
    public function expense_type_creation_requires_valid_data()
    {
        $this->actingAs($this->user)
            ->post(route('expense_type.v2.new'), [])
            ->assertSessionHasErrors(['type', 'description']);
    }

    /** @test */
    public function expense_type_must_be_unique()
    {
        ExpenseType::factory()->create(['type' => 'Office Supplies']);

        $this->actingAs($this->user)
            ->post(route('expense_type.v2.new'), [
                'type' => 'Office Supplies',
                'description' => 'Duplicate type'
            ])
            ->assertSessionHasErrors(['type']);
    }

    /** @test */
    public function user_can_view_expense_type_details()
    {
        $expenseType = ExpenseType::factory()->create(['created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('expense_type.v1.show', $expenseType->id))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_type.show');
    }

    /** @test */
    public function user_can_view_expense_type_edit_page()
    {
        $expenseType = ExpenseType::factory()->create(['created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('expense_type.v1.edit', $expenseType->id))
            ->assertStatus(200)
            ->assertViewIs('modules.expense_type.edit');
    }

    /** @test */
    public function user_can_update_expense_type()
    {
        $expenseType = ExpenseType::factory()->create(['created_by' => $this->user->id]);

        $updateData = [
            'type' => 'Updated Type Name',
            'description' => 'Updated description for the expense type'
        ];

        $this->actingAs($this->user)
            ->post(route('expense_type.v2.edit', $expenseType->id), $updateData)
            ->assertRedirect(route('expense_type.v1.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expense_type', [
            'id' => $expenseType->id,
            'type' => 'Updated Type Name',
            'description' => 'Updated description for the expense type',
            'updated_by' => $this->user->id
        ]);
    }

    /** @test */
    public function user_can_delete_expense_type()
    {
        $expenseType = ExpenseType::factory()->create(['created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->post(route('expense_type.v2.delete', $expenseType->id))
            ->assertRedirect(route('expense_type.v1.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expense_type', [
            'id' => $expenseType->id,
            'deleted' => 1,
            'deleted_by' => $this->user->id
        ]);
    }

    /** @test */
    public function user_can_export_expense_types_to_csv()
    {
        ExpenseType::factory()->create(['created_by' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('expense_type.v1.index', ['export' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    /** @test */
    public function user_can_bulk_delete_expense_types()
    {
        $type1 = ExpenseType::factory()->create(['created_by' => $this->user->id]);
        $type2 = ExpenseType::factory()->create(['created_by' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('expense_type.v1.index', [
                'bulk_delete' => $type1->id . ',' . $type2->id
            ]))
            ->assertRedirect(route('expense_type.v1.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expense_type', ['id' => $type1->id, 'deleted' => 1]);
        $this->assertDatabaseHas('expense_type', ['id' => $type2->id, 'deleted' => 1]);
    }

    /** @test */
    public function expense_type_description_has_max_length()
    {
        $longDescription = str_repeat('a', 1001);

        $this->actingAs($this->user)
            ->post(route('expense_type.v2.new'), [
                'type' => 'Test Type',
                'description' => $longDescription
            ])
            ->assertSessionHasErrors(['description']);
    }

    /** @test */
    public function expense_type_name_has_max_length()
    {
        $longTypeName = str_repeat('a', 256);

        $this->actingAs($this->user)
            ->post(route('expense_type.v2.new'), [
                'type' => $longTypeName,
                'description' => 'Valid description'
            ])
            ->assertSessionHasErrors(['type']);
    }
}