@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Products</h2>
                    <div class="btn-group" aria-label="Product Actions">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
                        <a href="{{ route('products.export') }}" class="btn btn-success">Export Products to CSV</a>
                        <a href="{{ route('products.export') }}" class="btn btn-secondary">Export Products</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="number" name="min_price" class="form-control" placeholder="Min price" value="{{ request('min_price') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="number" name="max_price" class="form-control" placeholder="Max price" value="{{ request('max_price') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ Str::limit($product->description, 50) }}</td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->subcategory->name }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
