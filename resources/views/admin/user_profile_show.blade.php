@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-7 col-lg-8">
            <h4 class="mb-3">Profile Detail</h4>
            <form>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="profile_image" class="form-label">
                            Profile image
                        </label>
                        <div>
                            <img class="bg-white" src="{{ asset($user->GetAvatar()) }}" alt="profile_image" width="200px">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label for="firstName" class="form-label">First name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="" value="{{ $user->first_name }}"
                            disabled>
                    </div>

                    <div class="col-sm-6">
                        <label for="lastName" class="form-label">Last name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="" value="{{ $user->last_name }}"
                            disabled>
                    </div>

                    <div class="col-12">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group has-validation">
                            <input type="email" class="form-control" id="username" placeholder="example@gmail.com" value="{{ $user->email }}" disabled>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" disabled>
                            <option value="">-</option>
                            @foreach ($genders as $g)
                                <option value="{{ $g->id }}" {{ ($user->gender_id != null && $g->id == $user->gender_id) ? 'selected' : '' }}>{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="role" class="form-label">Role</label>
                        <?php
                            $roles = '';
                            foreach ($user->user_roles as $user_role) {
                                $roles .= $user_role->role->name.', ';
                            }
                            $roles = trim($roles);
                            $roles = trim($roles, ",");
                        ?>
                        <input type="text" value="{{ $roles }}" class="form-control" id="role" placeholder="-" disabled>
                    </div>

                    <div class="col-md-4">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" id="department" disabled>
                            <option value="">Choose...</option>
                            @foreach ($departments as $d)
                                <option value="{{ $d->id }}" {{ ($user->department_id != null && $d->id == $user->department_id) ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-around mt-5 row">
                    <a class="text-white text-decoration-none btn btn-danger btn-lg w-25" href="{{ route('users.index') }}">Back</a>
                    <a class="text-black text-decoration-none btn btn-info btn-lg w-25" href="{{ route('users.edit', ['user' => $user->id]) }}">Edit</a>
                </div>
            </form>
        </div>
    </div>
@endsection
