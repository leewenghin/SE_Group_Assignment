@extends('layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between">
        <h1>
            User List
        </h1>
        <div>
            <a href="{{ route('users.create') }}"><img src="{{ asset('/images/plus.png') }}" alt="plug.png"></a>
        </div>
    </div>
    @include('components.action_message')
    <table id="userProfileTable" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th scope="col" class="d-none d-sm-table-cell">No</th>
            <th scope="col" class="d-none d-sm-table-cell">Username</th>
            <th scope="col" >Full Name</th>
            <th scope="col" >Role</th>
            <th scope="col" class="d-none d-sm-table-cell">Department</th>
            <th scope="col" >Action</th>
          </tr>
        </thead>
        <tbody class="table-group-divider table-secondary">
            {{-- zi keong - loop all the content base on the database, --}}
            @foreach ($users as $user)
                <tr>
                    <th class="fw-bold d-none d-sm-table-cell" scope="row">{{ $user->id }}</th>
                    <td class="d-none d-sm-table-cell">{{ $user->email }}</td>
                    <td>{{ $user->first_name.' '.$user->last_name }}</td>
                    <td>
                        @foreach ($user->user_roles as $user_role)
                            <p class="mb-0">- {{ $user_role->role->name }}</p>
                        @endforeach
                    </td>
                    <td class="d-none d-sm-table-cell">{{ $user->department->name ?? '-' }}</td>
                    <td>
                        <div class="d-inline-flex w-100 justify-content-around">
                            <a href="{{ route('users.show', ['user' => $user->id]) }}"><i class="fa-solid fa-file-lines view_icon me-2"></i></a>
                            <a href="{{ route('users.edit', ['user' => $user->id]) }}"><i class="fa-solid fa-pen-to-square edit_icon"></i></a>

                            <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="image" src="{{ asset('/images/delete.png') }}" alt="delete.png" width="30px">
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
    @if ($users->count() == 0)
        <div class="alert btn-info-user-list alert-dismissible fade show" role="alert">
            <strong>You haven't create any user profile before.</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

@endsection
