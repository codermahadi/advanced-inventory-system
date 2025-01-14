@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>@yield('table-title')</span>
                    @yield('table-actions')
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @yield('table-filters')

                    <div class="table-responsive">
                        @yield('table-content')
                    </div>

                    @yield('table-pagination')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
