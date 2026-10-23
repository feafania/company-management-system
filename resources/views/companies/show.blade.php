@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $company->name }}</h1>
        <div>
           <a href="{{ route('companies.edit', ['company' => $company, 'page' => request('page', 1)]) }}" class="btn btn-primary">Edit</a>
           <a href="{{ route('employees.create', ['company_id' => $company->id]) }}" class="btn btn-outline-primary">Add employee</a>
           <a href="{{ route('employees.index', ['company_id' => $company->id]) }}" class="btn btn-outline-secondary">All employees</a>
           <a href="{{ route('companies.index', ['page' => request('page', 1)]) }}" class="btn btn-outline-secondary">Back to list</a>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    @if ($company->logo)
                        <img src="{{ $company->logo_url }}"
                             alt="{{ $company->name }} logo"
                             class="img-fluid img-thumbnail">
                    @else
                        <div class="border rounded bg-light text-muted d-flex align-items-center justify-content-center"
                             style="height: 160px;">
                            No logo
                        </div>
                    @endif
                </div>

                <div class="col-md-9">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $company->name }}</dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $company->email ?? '—' }}</dd>

                        <dt class="col-sm-3">Website</dt>
                        <dd class="col-sm-9">
                            @if ($company->website)
                                <a href="{{ $company->website }}" target="_blank" rel="noopener">{{ $company->website }}</a>
                            @else
                                —
                            @endif
                        </dd>

                        <dt class="col-sm-3">Employees</dt>
                        <dd class="col-sm-9">{{ $company->employees_count }}</dd>

                        <dt class="col-sm-3">Created</dt>
                        <dd class="col-sm-9">{{ $company->created_at->format('Y-m-d H:i') }}</dd>

                        <dt class="col-sm-3">Updated</dt>
                        <dd class="col-sm-9">{{ $company->updated_at->format('Y-m-d H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

     @if ($company->employees->isNotEmpty())
            <h2 class="h5 mt-4">Employees</h2>
            <ul class="list-group">
                @foreach ($company->employees as $employee)
                    <li class="list-group-item">
                        <a href="{{ route('employees.show', $employee) }}">{{ $employee->first_name }} {{ $employee->last_name }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
</div>
@endsection
