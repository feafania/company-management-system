<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Filesystem\FilesystemAdapter;


class CompanyTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private FilesystemAdapter $disk;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->disk = Storage::disk('public');
        $this->admin = User::factory()->create();

    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Acme Ltd',
            'email'   => 'info@acme.test',
            'website' => 'https://acme.test',
        ], $overrides);
    }

    // ---------- Authentication ----------

    public function test_guests_are_redirected_to_login(): void
    {
        $company = Company::factory()->create();

        $this->get(route('companies.index'))->assertRedirect(route('login'));
        $this->get(route('companies.create'))->assertRedirect(route('login'));
        $this->post(route('companies.store'), $this->validData())->assertRedirect(route('login'));
        $this->get(route('companies.show', $company))->assertRedirect(route('login'));
        $this->get(route('companies.edit', $company))->assertRedirect(route('login'));
        $this->put(route('companies.update', $company), $this->validData())->assertRedirect(route('login'));
        $this->delete(route('companies.destroy', $company))->assertRedirect(route('login'));

        $this->assertDatabaseCount('companies', 1);
    }

    public function test_registration_route_does_not_exist(): void
    {
        $this->get('/register')->assertNotFound();
    }

    // ---------- Read ----------

    public function test_index_is_paginated_by_ten(): void
    {
        Company::factory()->count(15)->create();

        $this->actingAs($this->admin)->get(route('companies.index'))
            ->assertOk()
            ->assertViewHas('companies', fn ($c) => $c->count() === 10 && $c->total() === 15);

        $this->actingAs($this->admin)->get(route('companies.index', ['page' => 2]))
            ->assertViewHas('companies', fn ($c) => $c->count() === 5);
    }

    public function test_show_displays_company(): void
    {
        $company = Company::factory()->create(['name' => 'Show Me Inc']);

        $this->actingAs($this->admin)->get(route('companies.show', $company))
            ->assertOk()
            ->assertSee('Show Me Inc');
    }

    // ---------- Create ----------

    public function test_admin_can_create_company_with_logo(): void
    {
        $logo = UploadedFile::fake()->image('logo.png', 200, 200);

        $this->actingAs($this->admin)
            ->post(route('companies.store'), $this->validData(['logo' => $logo]))
            ->assertRedirect(route('companies.index'))
            ->assertSessionHas('status');

        $company = Company::query()->firstWhere('name', 'Acme Ltd');
        $this->assertNotNull($company);
        $this->assertNotNull($company->logo);
        $this->disk->assertExists($company->logo);
    }

    public function test_company_can_be_created_without_optional_fields(): void
    {
        $this->actingAs($this->admin)
            ->post(route('companies.store'), ['name' => 'Only Name'])
            ->assertRedirect(route('companies.index'));

        $this->assertDatabaseHas('companies', ['name' => 'Only Name', 'logo' => null]);
    }

    // ---------- Validation ----------

    public function test_name_is_required(): void
    {
        $this->actingAs($this->admin)
            ->from(route('companies.create'))
            ->post(route('companies.store'), $this->validData(['name' => '']))
            ->assertRedirect(route('companies.create'))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('companies', 0);
    }

    public function test_email_and_website_must_be_valid(): void
    {
        $this->actingAs($this->admin)
            ->post(route('companies.store'), $this->validData(['email' => 'abc', 'website' => 'not-a-url']))
            ->assertSessionHasErrors(['email', 'website']);
    }

    public function test_logo_must_be_at_least_100_by_100(): void
    {
        $this->actingAs($this->admin)
            ->post(route('companies.store'), $this->validData([
                'logo' => UploadedFile::fake()->image('small.png', 50, 50),
            ]))
            ->assertSessionHasErrors('logo');
    }

    public function test_logo_must_be_jpg_or_png(): void
    {
        $this->actingAs($this->admin)
            ->post(route('companies.store'), $this->validData([
                'logo' => UploadedFile::fake()->image('logo.gif', 200, 200),
            ]))
            ->assertSessionHasErrors('logo');

        $this->actingAs($this->admin)
            ->post(route('companies.store'), $this->validData([
                'logo' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
            ]))
            ->assertSessionHasErrors('logo');
    }

    public function test_logo_must_not_exceed_2mb(): void
    {
        $this->actingAs($this->admin)
            ->post(route('companies.store'), $this->validData([
                'logo' => UploadedFile::fake()->image('big.png', 300, 300)->size(3000),
            ]))
            ->assertSessionHasErrors('logo');
    }

    // ---------- Update ----------

    public function test_admin_can_update_company_and_logo_is_kept(): void
    {
        $path = UploadedFile::fake()->image('old.png', 200, 200)->store('logos', 'public');
        $company = Company::factory()->create(['logo' => $path]);

        $this->actingAs($this->admin)
            ->put(route('companies.update', $company), $this->validData(['name' => 'Renamed']))
            ->assertRedirect(route('companies.show', ['company' => $company, 'page' => 1]));
           // ->assertRedirect(route('companies.index', ['page' => 1]));

        $company->refresh();
        $this->assertSame('Renamed', $company->name);
        $this->assertSame($path, $company->logo);
        $this->disk->assertExists($path);
    }

    public function test_updating_logo_removes_the_old_file(): void
    {
        $old = UploadedFile::fake()->image('old.png', 200, 200)->store('logos', 'public');
        $company = Company::factory()->create(['logo' => $old]);

        $this->actingAs($this->admin)
            ->put(route('companies.update', $company), $this->validData([
                'logo' => UploadedFile::fake()->image('new.jpg', 300, 300),
            ]))
            ->assertSessionHasNoErrors();

        $company->refresh();
        $this->assertNotSame($old, $company->logo);
        $this->disk->assertMissing($old);
        $this->disk->assertExists($company->logo);
    }

    public function test_update_validates_input(): void
    {
        $company = Company::factory()->create();

        $this->actingAs($this->admin)
            ->put(route('companies.update', $company), $this->validData(['name' => '']))
            ->assertSessionHasErrors('name');
    }

    public function test_update_redirect_keeps_page_number(): void
    {
        $company = Company::factory()->create();

        $this->actingAs($this->admin)
            ->put(route('companies.update', ['company' => $company, 'page' => 3]), $this->validData())
            ->assertRedirect(route('companies.show', ['company' => $company, 'page' => 3]));
           // ->assertRedirect(route('companies.index', ['page' => 3]));
    }

    // ---------- Delete ----------

    public function test_deleting_company_removes_logo_and_employees(): void
    {
        $path = UploadedFile::fake()->image('logo.png', 200, 200)->store('logos', 'public');
        $company = Company::factory()->create(['logo' => $path]);
        Employee::factory()->count(2)->create(['company_id' => $company->id]);

        $this->actingAs($this->admin)
            ->delete(route('companies.destroy', $company))
            ->assertRedirect(route('companies.index', ['page' => 1]))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
        $this->assertDatabaseCount('employees', 0);
        $this->disk->assertMissing($path);
    }

    public function test_deleting_last_item_on_last_page_redirects_to_previous_page(): void
    {
        $companies = Company::factory()->count(11)->create()->sortBy('name')->values();
        $last = $companies->last();

        $this->actingAs($this->admin)
            ->delete(route('companies.destroy', $last), ['page' => 2])
            ->assertRedirect(route('companies.index', ['page' => 1]));
    }
}
