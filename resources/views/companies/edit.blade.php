@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-3">Edit company</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('companies.update', ['company' => $company, 'page' => request('page', 1)]) }}" method="POST"
                  enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $company->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $company->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Logo</label>
                    <div class="mb-2">
                        <img id="logo-preview"
                             src="{{ $company->logo ? asset('storage/' . $company->logo) : '' }}"
                             alt="Logo preview" height="80"
                             class="img-thumbnail {{ $company->logo ? '' : 'd-none' }}">
                    </div>
                    <input type="file" id="logo" name="logo" accept="image/*"
                           class="form-control @error('logo') is-invalid @enderror">
                    <div class="form-text">
                        Minimum size 100×100 pixels, maximum 2 MB. Leave empty to keep the current logo.
                    </div>
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="website" class="form-label">Website</label>
                    <input type="url" id="website" name="website"
                           class="form-control @error('website') is-invalid @enderror"
                           value="{{ old('website', $company->website) }}" placeholder="https://example.com">
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('companies.show', ['company' => $company, 'page' => request('page', 1)]) }}" class="btn btn-link">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection
