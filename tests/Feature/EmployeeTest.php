<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Anna',
            'last_name'  => 'Kowalska',
            'company_id' => Company::factory()->create()->id,
            'email'      => 'anna@example.test',
            'phone'      => '+375 29 123 45 67',
        ], $overrides);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $employee = Employee::factory()->create();

        $this->get(route('employees.index'))->assertRedirect(route('login'));
        $this->get(route('employees.create'))->assertRedirect(route('login'));
        $this->post(route('employees.store'), [])->assertRedirect(route('login'));
        $this->get(route('employees.show', $employee))->assertRedirect(route('login'));
        $this->get(route('employees.edit', $employee))->assertRedirect(route('login'));
        $this->put(route('employees.update', $employee), [])->assertRedirect(route('login'));
        $this->delete(route('employees.destroy', $employee))->assertRedirect(route('login'));
    }

    public function test_index_is_paginated_by_ten_and_shows_company(): void
    {
        $company = Company::factory()->create(['name' => 'Zeta Corp']);
        Employee::factory()->count(15)->create(['company_id' => $company->id]);

        $this->actingAs($this->admin)->get(route('employees.index'))
            ->assertOk()
            ->assertSee('Zeta Corp')
            ->assertViewHas('employees', fn ($e) => $e->count() === 10 && $e->total() === 15);

        $this->actingAs($this->admin)->get(route('employees.index', ['page' => 2]))
            ->assertViewHas('employees', fn ($e) => $e->count() === 5);
    }

    public function test_create_form_lists_companies(): void
    {
        Company::factory()->create(['name' => 'Listed Co']);

        $this->actingAs($this->admin)->get(route('employees.create'))
            ->assertOk()
            ->assertSee('Listed Co');
    }

    public function test_admin_can_create_employee(): void
    {
        $data = $this->validData();

        $this->actingAs($this->admin)
            ->post(route('employees.store'), $data)
            ->assertRedirect(route('employees.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('employees', [
            'first_name' => 'Anna',
            'last_name'  => 'Kowalska',
            'company_id' => $data['company_id'],
            'email'      => 'anna@example.test',
            'phone'      => '+375 29 123 45 67',
        ]);
    }

    public function test_employee_can_be_created_with_required_fields_only(): void
    {
        $companyId = Company::factory()->create()->id;

        $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'Min',
                'last_name'  => 'Imal',
                'company_id' => $companyId,
            ])
            ->assertRedirect(route('employees.index'));

        $this->assertDatabaseHas('employees', ['first_name' => 'Min', 'email' => null, 'phone' => null]);
    }

    public function test_required_fields_are_validated(): void
    {
        $this->actingAs($this->admin)
            ->from(route('employees.create'))
            ->post(route('employees.store'), [])
            ->assertRedirect(route('employees.create'))
            ->assertSessionHasErrors(['first_name', 'last_name', 'company_id']);

        $this->assertDatabaseCount('employees', 0);
    }

    public function test_company_must_exist(): void
    {
        $this->actingAs($this->admin)
            ->post(route('employees.store'), $this->validData(['company_id' => 9999]))
            ->assertSessionHasErrors('company_id');
    }

    public function test_email_must_be_valid(): void
    {
        $this->actingAs($this->admin)
            ->post(route('employees.store'), $this->validData(['email' => 'abc']))
            ->assertSessionHasErrors('email');
    }

    public function test_phone_rules(): void
    {
        $this->actingAs($this->admin)
            ->post(route('employees.store'), $this->validData(['phone' => 'abc']))
            ->assertSessionHasErrors('phone');

        $this->actingAs($this->admin)
            ->post(route('employees.store'), $this->validData(['phone' => str_repeat('1', 21)]))
            ->assertSessionHasErrors('phone');
    }

    public function test_show_displays_employee_and_company_link(): void
    {
        $company = Company::factory()->create(['name' => 'Linked Co']);
        $employee = Employee::factory()->create([
            'first_name' => 'Jan',
            'last_name'  => 'Nowak',
            'company_id' => $company->id,
        ]);

        $this->actingAs($this->admin)->get(route('employees.show', $employee))
            ->assertOk()
            ->assertSee('Jan')
            ->assertSee('Nowak')
            ->assertSee('Linked Co')
            ->assertSee(route('companies.show', $company), false);
    }

    public function test_edit_form_is_prefilled_and_company_preselected(): void
    {
        $company = Company::factory()->create(['name' => 'Pre Co']);
        $employee = Employee::factory()->create([
            'first_name' => 'Edit',
            'company_id' => $company->id,
        ]);

        $this->actingAs($this->admin)->get(route('employees.edit', $employee))
            ->assertOk()
            ->assertSee('value="Edit"', false)
            ->assertSee('value="' . $company->id . '" selected', false);
    }

    public function test_admin_can_update_employee(): void
    {
        $employee = Employee::factory()->create();
        $newCompany = Company::factory()->create();

        $this->actingAs($this->admin)
            ->put(route('employees.update', ['employee' => $employee, 'page' => 2]), $this->validData([
                'first_name' => 'Changed',
                'company_id' => $newCompany->id,
            ]))
            ->assertRedirect(route('employees.index', ['page' => 2]));

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => 'Changed',
            'company_id' => $newCompany->id,
        ]);
    }

    public function test_update_validates_input(): void
    {
        $employee = Employee::factory()->create();

        $this->actingAs($this->admin)
            ->put(route('employees.update', $employee), $this->validData(['first_name' => '']))
            ->assertSessionHasErrors('first_name');

        $this->assertDatabaseMissing('employees', ['id' => $employee->id, 'first_name' => '']);
    }

    public function test_admin_can_delete_employee(): void
    {
        $employee = Employee::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('employees.destroy', $employee))
            ->assertRedirect(route('employees.index', ['page' => 1]))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_deleting_company_removes_its_employees(): void
    {
        $company = Company::factory()->create();
        Employee::factory()->count(3)->create(['company_id' => $company->id]);

        $company->delete();

        $this->assertDatabaseCount('employees', 0);
    }

    public function test_index_can_be_filtered_by_company(): void
    {
        $a = Company::factory()->create(['name' => 'Alpha Co']);
        $b = Company::factory()->create(['name' => 'Beta Co']);
        Employee::factory()->count(2)->create(['company_id' => $a->id]);
        Employee::factory()->count(3)->create(['company_id' => $b->id]);

        $this->actingAs($this->admin)
            ->get(route('employees.index', ['company_id' => $b->id]))
            ->assertOk()
            ->assertViewHas('employees', fn ($e) => $e->total() === 3);
    }

    public function test_success_message_is_shown_after_creating_employee(): void
    {
        $this->actingAs($this->admin)
            ->followingRedirects()
            ->post(route('employees.store'), $this->validData())
            ->assertSee('Employee created.');
    }

    public function test_create_form_preselects_company_from_query(): void
    {
        $company = Company::factory()->create(['name' => 'Preset Co']);

        $this->actingAs($this->admin)
            ->get(route('employees.create', ['company_id' => $company->id]))
            ->assertSeeInOrder(['value="' . $company->id . '"', 'selected', 'Preset Co'], false);
    }
}
