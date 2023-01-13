@extends('layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between">
        <h1>
            Manage Department
        </h1>
        <div>
            <a href="{{ route('departments.create') }}"><img src="{{ asset('images/plus.png') }}" alt="plus.png"></a>
        </div>
    </div>
    @include('components.action_message')
    <table id="adminDepartTable" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col" class="d-none d-sm-table-cell">No</th>
                <th scope="col">Department Name</th>
                <th scope="col">Description</th>
                <th scope="col" class="d-none d-sm-table-cell">Date Created</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody class="table-group-divider table-secondary standard_content">
            @foreach ($departments as $department)
                <tr>
                    <th class="fw-bold d-none d-sm-table-cell" scope="row">{{ $department->id }}</th>
                    <td>{{ $department->name }}</td>
                    <td>{{ $department->description }}</td>
                    <td class="d-none d-sm-table-cell">{{ $department->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-inline-flex w-100 justify-content-around">
                            <a href="{{ route('departments.edit', ['department' => $department->id]) }}"><i class="fa-solid fa-pen-to-square edit_icon me-2"></i></a>

                            <form action="{{ route('departments.destroy', ['department' => $department->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="image" src="{{ asset('images/delete.png') }}" alt="delete.png" width="30px">
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if ($departments->count() == 0)
        <div class="alert btn-info-user-list alert-dismissible fade show" role="alert">
            <strong>You haven't create any user profile before.</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

@endsection
