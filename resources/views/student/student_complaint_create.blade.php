@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-7 col-lg-8">
            <h3 class="mb-3 text-center">We are here to assist you!</h3>
            <p class="h5 mb-4 text-center">Please complete the form below your complaints</p>
            <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="" required>
                        <div class="invalid-feedback">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="" rows="5" cols="10" required>{{ old('description') }}</textarea>
                        <div class="invalid-feedback">
                            @error('description')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="student_upload_image_video" class="form-label">
                            Upload image / video
                        </label>
                        <input id="student_upload_image_video" class="form-control @error('file') is-invalid @enderror" type="file" name="file" class="hidden" required />
                        <div class="invalid-feedback">
                            @error('file')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-around mt-5 row">
                    <a class="text-white text-decoration-none btn btn-danger btn-lg w-25" href="{{ route('complaints.index') }}"> Back</a>
                    <button class="btn btn-primary btn-lg w-25" type="submit">Save</button>
                </div>

                <!-- Button trigger modal -->
                <button id="fileUploadWarningModelBtn" type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#fileUploadWarningModel">
                    Launch demo modal
                </button>

            </form>
        </div>
    </div>
    @include('components.file_upload_warning_model')
@endsection
