@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-7 col-lg-8">
            <h4 class="mb-3">Edit Department</h4>
            <form class="needs-validation" novalidate="" action="{{ route('departments.update', ['department' => $department->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="col-sm-12">
                        <label for="departmentName" class="form-label">Department Name: </label>
                        <input type="text" name="name" value="{{ old('name', $department->name) }}" class="form-control @error('name') is-invalid @enderror" id="departmentName">
                        <div class="invalid-feedback">
                            @error('name')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="4" cols="50">{{ old('description', $department->description) }}</textarea>
                        <div class="invalid-feedback">
                            @error('description')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-around mt-5 row">
                    <a class="text-white text-decoration-none btn btn-danger btn-lg w-25" href="{{ route('departments.index') }}"> Back</a>
                    <button class="btn btn-primary btn-lg w-25" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
