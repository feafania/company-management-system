@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-3">Add employee</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('employees.store') }}" method="POST" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="first_name" class="form-label">First name <span class="text-danger">*</span></label>
                    <input type="text" id="first_name" name="first_name"
                           class="form-control @error('first_name') is-invalid @enderror"
                           value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">Last name <span class="text-danger">*</span></label>
                    <input type="text" id="last_name" name="last_name"
                           class="form-control @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name') }}" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                    <select id="company_id" name="company_id"
                            class="form-select @error('company_id') is-invalid @enderror" required>
                        <option value="">Select a company</option>
                        @foreach ($companies as $company)
                            @php
                                $isSelected = (string) old('company_id', request('company_id')) === (string) $company->id;
                            @endphp
                            <option value="{{ $company->id }}" @selected($isSelected)>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        $isSelected = (string) old('company_id', request('company_id')) === (string) $company->id;
                        @endphp
                        <option value="{{ $company->id }}" @selected($isSelected)>
                            {{ $company->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" id="phone" name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" placeholder="+375 29 123 45 67">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ request('company_id') ? route('companies.show', request('company_id')) : route('employees.index') }}"
                       class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
