@extends('layouts.form')

@section('form-title', 'Create Category')

@section('form-content')
<form action="{{ route('categories.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Category Name</label>
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               id="name"
               name="name"
               value="{{ old('name') }}"
               required>
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Category</button>
    </div>
</form>
@endsection
