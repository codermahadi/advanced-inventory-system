@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        Products
                    </a>
                    <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        Categories
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            @yield('dashboard-content')
        </div>
    </div>
</div>
@endsection
