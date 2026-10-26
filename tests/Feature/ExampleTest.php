<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_root_redirects_to_home(): void
    {
        $this->get('/')
            ->assertRedirect('/home');
    }

    public function test_authenticated_user_can_open_home(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/home')
            ->assertOk()
            ->assertSee('Companies')
            ->assertSee('Employees');
    }
}
