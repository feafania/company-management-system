@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <h1 class="h3 mb-0">
            Employees
            @if ($company)
            <span class="text-muted d-block d-sm-inline">of {{ $company->name }}</span>
            @endif
        </h1>
        <div class="d-flex flex-wrap gap-2">
            @if ($company)
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Show all</a>
            @endif
            <a href="{{ route('employees.create', ['company_id' => $company?->id]) }}" class="btn btn-primary">Add employee</a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Company</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-lg-table-cell">Phone</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->first_name }}</td>
                            <td>{{ $employee->last_name }}</td>
                            <td>
                                <a href="{{ route('companies.show', $employee->company) }}">{{ $employee->company->name }}</a>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $employee->email }}</td>
                            <td class="d-none d-lg-table-cell">{{ $employee->phone }}</td>
                            <td class="text-end text-nowrap">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('employees.show', ['employee' => $employee, 'page' => $employees->currentPage()]) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       aria-label="View {{ $employee->first_name }} {{ $employee->last_name }}"
                                       title="View">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                    </a>

                                    <a href="{{ route('employees.edit', ['employee' => $employee, 'page' => $employees->currentPage()]) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       aria-label="Edit {{ $employee->first_name }} {{ $employee->last_name }}"
                                       title="Edit">
                                        <i class="bi bi-pencil" aria-hidden="true"></i>
                                    </a>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-action="{{ route('employees.destroy', $employee) }}"
                                            data-name="{{ $employee->first_name }} {{ $employee->last_name }}"
                                            data-hint=""
                                            aria-label="Delete {{ $employee->first_name }} {{ $employee->last_name }}"
                                            title="Delete">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No employees yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 overflow-auto">
        {{ $employees->links() }}
    </div>

    @include('partials.delete-modal')
</div>
@endsection
