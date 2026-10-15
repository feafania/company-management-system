<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'=> fake()->firstName(),
            'last_name'=> fake()->lastName(),
            'company_id'=> Company::factory(),
            'email'=> fake()->unique()->safeEmail(),
            'phone'=> fake()->numerify('+###########'),
        ];
    }
}
