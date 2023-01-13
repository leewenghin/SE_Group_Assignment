@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-7 col-lg-8">
            <div class="d-flex justify-content-between">
                <h3 class="mb-3 text-center">Complaint Detail</h3>
                <div class="btn btn-lg text-bg-{{ $complaint->status->GetColor() }}">
                    {{ $complaint->status->name }}
                </div>
            </div>

            <form action="{{ route('complaints.update', ['complaint' => $complaint->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $complaint->title) }}" placeholder="" required>
                        <div class="invalid-feedback">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="" rows="5" cols="10" required>{{ old('description', $complaint->description) }}</textarea>
                        <div class="invalid-feedback">
                            @error('description')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    {{-- zi keong  please follow the file load the image and video--}}
                    @if ($complaint->img_or_video_path != null && $complaint->img_or_video_name != null)
                        @if ($complaint->is_video)
                            <div class="col-sm-6 col-12">
                                <label for="problem_video" class="form-label">Video</label>
                                <video id="problem_video" width="100%" height="auto" controls>
                                    <source src="{{ asset($complaint->img_or_video_path.$complaint->img_or_video_name) }}" type="{{ $complaint->mime_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @else
                            <div class="col-sm-6 col-12">
                                <label for="problem_video" class="form-label">Image</label>
                                <img src="{{ asset($complaint->img_or_video_path.$complaint->img_or_video_name) }}" alt="problem.jpg" width="100%">
                            </div>
                        @endif
                    @endif

                    <div class="col-12">
                        <label for="profile_image" class="form-label">
                            Upload new image / video
                        </label>
                        <input id="student_upload_image_video" class="form-control @error('file') is-invalid @enderror" type="file" name="file" class="hidden" />
                        <div class="invalid-feedback">
                            @error('file')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-around mt-5 row">
                    <a class="text-white text-decoration-none btn btn-danger btn-lg w-25" href="{{ route('complaints.show', ['complaint' => $complaint->id]) }}">Back</a>
                    <button class="btn btn-primary btn-lg w-25" type="submit">Save</button>
                </div>

                <!-- Button trigger modal -->
                <button id="fileUploadWarningModelBtn" type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#fileUploadWarningModel">
                    hidden model to show a notice
                </button>
            </form>
        </div>
    </div>
    @include('components.file_upload_warning_model')
@endsection
