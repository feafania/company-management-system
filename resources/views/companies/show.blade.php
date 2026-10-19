@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $company->name }}</h1>
        <div>
            <a href="{{ route('companies.edit', $company) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">Back to list</a>
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
                        <img src="{{ asset('storage/' . $company->logo) }}"
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
</div>
@endsection
