<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'companiesCount' => Company::query()->count(),
            'employeesCount' => Employee::query()->count(),
        ]);
    }
}
