@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">Dashboard</h1>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <a href="{{ route('companies.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 mb-1">Companies</h2>
                            <p class="text-muted mb-0">Manage companies and logos</p>
                        </div>
                        <span class="display-6 fw-semibold">{{ $companiesCount }}</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6">
            <a href="{{ route('employees.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 mb-1">Employees</h2>
                            <p class="text-muted mb-0">Manage employees and their companies</p>
                        </div>
                        <span class="display-6 fw-semibold">{{ $employeesCount }}</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
