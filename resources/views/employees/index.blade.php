@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">
            Employees
            @if ($company)
                <span class="text-muted">of {{ $company->name }}</span>
            @endif
        </h1>
        <div>
            @if ($company)
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Show all</a>
            @endif
            <a href="{{ route('employees.create', ['company_id' => $company?->id]) }}" class="btn btn-primary">Add employee</a>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
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
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td class="text-end">
                                <a href="{{ route('employees.show', ['employee' => $employee, 'page' => $employees->currentPage()]) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ route('employees.edit', ['employee' => $employee, 'page' => $employees->currentPage()]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-action="{{ route('employees.destroy', $employee) }}"
                                        data-name="{{ $employee->first_name }} {{ $employee->last_name }}"
                                        data-hint="">
                                    Delete
                                </button>
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

    <div class="mt-3">
        {{ $employees->links() }}
    </div>

    @include('partials.delete-modal')
</div>
@endsection
