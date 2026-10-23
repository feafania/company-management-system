<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::factory()
            ->count(25)
            ->create()
            ->each(function (Company $company) {
                Employee::factory()
                    ->count(random_int(2, 5))
                    ->create(['company_id' => $company->id]);
            });
    }
}
