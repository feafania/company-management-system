<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $companies = Company::query()
            ->withCount('employees')
            ->orderBy('name')
            ->paginate(10);

        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreCompanyRequest $request): RedirectResponse
   {
       $data = $request->validated();

       if ($request->hasFile('logo')) {
           $data['logo'] = $request->file('logo')->store('logos', 'public');
       }

       Company::query()->create($data);

       return redirect()->route('companies.index')->with('status', 'Company created.');
   }

    /**
     * Display the specified resource.
     */
    public function show(Company $company): View
    {
        $company->loadCount('employees');

        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company): View
    {
       return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        // dd($request->validated());
        $data = $request->safe()->except('logo');

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        return redirect()
            ->route('companies.show', ['company' => $company, 'page' => $request->query('page', 1)])
            ->with('status', 'Company updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
