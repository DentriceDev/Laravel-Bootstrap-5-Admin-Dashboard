@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
        @can('user_create')
        <a class="btn btn-primary float-end" href="{{ route('users.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
        </a>
        @endcan
    </div>

    <div class="card-body">
        @if(session('message'))
        <div class="col-md-12">
            <div class="alert alert-success d-flex align-items-center text-white alert-dismissible fade show" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class="bi flex-shrink-0 me-2" width="24" fill="white" height="24" role="img" aria-label="Success:">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                {{ session('message') }}
                <button type="button" class="btn-close bg-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @livewire('users-table')
    </div>
</div>



@endsection
