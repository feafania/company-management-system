@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Companies</h1>
        <a href="{{ route('companies.create') }}" class="btn btn-primary">Add company</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th>Employees</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($companies as $company)
                        <tr>
                            <td>
                                @if ($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}"
                                         alt="{{ $company->name }} logo" height="40">
                                @endif
                            </td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>
                                @if ($company->website)
                                    <a href="{{ $company->website }}" target="_blank" rel="noopener">{{ $company->website }}</a>
                                @endif
                            </td>
                            <td>{{ $company->employees_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('companies.show', ['company' => $company, 'page' => $companies->currentPage()]) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ route('companies.edit', ['company' => $company, 'page' => $companies->currentPage()]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('companies.destroy', $company) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this company and all its employees?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="page" value="{{ $companies->currentPage() }}">
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
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

    <div class="mt-3">
        {{ $companies->links() }}
    </div>
</div>
@endsection
