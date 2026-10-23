<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request): View
     {
         $company = $request->filled('company_id')
             ? Company::query()->find($request->integer('company_id'))
             : null;

         $employees = Employee::query()
             ->with('company')
             ->when($company, fn ($query) => $query->where('company_id', $company->id))
             ->orderBy('last_name')
             ->orderBy('first_name')
             ->paginate(10)
             ->withQueryString();

           return view('employees.index', compact('employees', 'company'));
       }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $companies = Company::query()->orderBy('name')->get(['id', 'name']);
        return view('employees.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        Employee::query()->create($request->validated());
        return redirect()->route('employees.index')->with('status', 'Employee created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee): View
    {
        $employee->load('company');
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee): View
    {
        $companies = Company::query()->orderBy('name')->get(['id', 'name']);
        return view('employees.edit', compact('employee', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()
            ->route('employees.index', ['page' => $request->query('page', 1)])
            ->with('status', 'Employee updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Employee $employee): RedirectResponse
    {
        try {
            $employee->delete();
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'The employee could not be deleted. Please try again.');
        }

        $page = max(1, (int) $request->input('page', 1));
        $lastPage = max(1, (int) ceil(Employee::query()->count() / 10));

        return redirect()
            ->route('employees.index', array_filter([
                'page' => min($page, $lastPage),
                'company_id' => $request->input('company_id'),
            ]))
            ->with('status', 'Employee deleted.');
    }
}
