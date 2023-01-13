@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Create User's Profile</h4>
        <form class="needs-validation" novalidate="" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="firstName" class="form-label">First name</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" id="firstName" placeholder="" required>
                    <div class="invalid-feedback">
                        @error('first_name')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <label for="lastName" class="form-label">Last name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" id="lastName" placeholder="" required>
                    <div class="invalid-feedback">
                        @error('last_name')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group has-validation">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="username" placeholder="example@gmail.com" required>
                        <div class="invalid-feedback">
                            @error('email')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group has-validation">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required>
                        <div class="invalid-feedback">
                            @error('password')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <label for="comfirm_password" class="form-label">Confirm Password</label>
                    <div class="input-group has-validation">
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="comfirm_password" required>
                        <div class="invalid-feedback">
                            @error('password_confirmation')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror" id="gender" required>
                        <option value="">Choose...</option>
                        @foreach ($genders as $gender)
                            <option value="{{ $gender->id }}" {{ ($gender->id == old('gender')) ? 'selected' : '' }}>{{ $gender->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        @error('gender')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" class="form-select @error('department') is-invalid @enderror" id="department" required>
                        <option value="">Choose...</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ ($department->id == old('department')) ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        @error('department')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                @for ($i = 0; $i < 3; $i++)
                    <div class="col-md-4">
                        <label for="role" class="form-label">Role</label>
                        <select name="role[]" class="form-select @error('role.'.$i) is-invalid @enderror" id="role" required>
                            <option value="">Choose...</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ ($role->id == old('role.'.$i)) ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            @error('role.'.$i)
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                @endfor
                <div id="roleSelectionHelpBlock" class="form-text">
                    By default, every account is assigned to a complainer role. If the user does not possess any additional roles, it is requested that the role field be left blank.
                </div>

                <div class="col-12">
                    <label for="profile_image" class="form-label">
                        Choose a profile image
                    </label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" id="profile_image" class="hidden">
                    <div class="invalid-feedback">
                        @error('file')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-around mt-5 row">
                <a href="{{ route('users.index') }}" class="text-white text-decoration-none btn btn-danger btn-lg w-25"> Back</a>
                <button type="submit" class="btn btn-primary btn-lg w-25">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
