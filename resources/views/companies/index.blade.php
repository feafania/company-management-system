@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
        <h1 class="h3 mb-0">Companies</h1>
        <a href="{{ route('companies.create') }}" class="btn btn-primary">Add company</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th class="d-none d-sm-table-cell">Logo</th>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-lg-table-cell">Website</th>
                        <th>Employees</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($companies as $company)
                        <tr>
                            <td class="d-none d-sm-table-cell">
                                @if ($company->logo)
                                    <img src="{{ $company->logo_url }}"
                                         alt="{{ $company->name }} logo" height="40">
                                @endif
                            </td>
                            <td>{{ $company->name }}</td>
                            <td class="d-none d-md-table-cell">{{ $company->email }}</td>
                            <td class="d-none d-lg-table-cell">
                                @if ($company->website)
                                    <a href="{{ $company->website }}" target="_blank" rel="noopener">{{ $company->website }}</a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('employees.index', ['company_id' => $company->id]) }}">{{ $company->employees_count }}</a>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                                    <a href="{{ route('companies.show', ['company' => $company, 'page' => $companies->currentPage()]) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    <a href="{{ route('companies.edit', ['company' => $company, 'page' => $companies->currentPage()]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-action="{{ route('companies.destroy', $company) }}"
                                            data-name="{{ $company->name }}"
                                            data-hint="All employees of this company will be deleted too.">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No companies yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 overflow-auto">
        {{ $companies->links() }}
    </div>

    @include('partials.delete-modal')
</div>
@endsection
