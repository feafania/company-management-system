@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
        <h1 class="h3 mb-0 text-break">{{ $employee->first_name }} {{ $employee->last_name }}</h1>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('employees.edit', ['employee' => $employee, 'page' => request('page', 1)]) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('employees.index', ['page' => request('page', 1)]) }}" class="btn btn-outline-secondary">Back to list</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">First name</dt>
                <dd class="col-sm-9">{{ $employee->first_name }}</dd>

                <dt class="col-sm-3">Last name</dt>
                <dd class="col-sm-9">{{ $employee->last_name }}</dd>

                <dt class="col-sm-3">Company</dt>
                <dd class="col-sm-9">
                    <a href="{{ route('companies.show', $employee->company) }}">{{ $employee->company->name }}</a>
                </dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $employee->email ?? '—' }}</dd>

                <dt class="col-sm-3">Phone</dt>
                <dd class="col-sm-9">{{ $employee->phone ?? '—' }}</dd>

                <dt class="col-sm-3">Created</dt>
                <dd class="col-sm-9">{{ $employee->created_at->format('Y-m-d H:i') }}</dd>

                <dt class="col-sm-3">Updated</dt>
                <dd class="col-sm-9">{{ $employee->updated_at->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
