@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-7 col-lg-8">

            {{-- zi keong - if the status is closed then here need to show the comment of the helpdesk --}}
            @if ($complaint->status_id == 6 && $complaint->verified_complaint_id != null && $complaint->verified_complaint->complaint_action_id == 9)
                <div class="mt-3 mb-5 col-lg-6 col-12">
                    {{-- helpdesk name --}}
                    <?php
                        $last_log = $complaint->verified_complaint->complaint_loggings->sortBy('created_at')->last(function ($value, $key) {
                            return $value->complaint_action_id == 9;
                        });
                    ?>
                    <h3>Replay by helpdesk: {{ ($last_log->user_id != null) ? $last_log->user->first_name.' '.$last_log->user->last_name : '' }}</h3>

                    <div class="fs-5 text-bg-light p-3 rounded">
                        {{ $complaint->verified_complaint->finalize_remark }}
                    </div>
                </div>
            @endif


            <div class="d-flex justify-content-between">
                <h3 class="mb-3 text-center">Complaint Detail</h3>

                <div class="btn btn-lg text-bg-{{ $complaint->status->GetColor() }}">
                    {{ $complaint->status->name }}
                </div>
            </div>

            <form>
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="" value="{{ $complaint->title }}" disabled>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <div class="input-group has-validation">
                            <textarea type="email" class="form-control" id="description" name="description" placeholder="" rows="5" cols="10" disabled>{{ $complaint->description }}</textarea>
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
                </div>

                <div class="d-flex justify-content-around mt-5 row">
                    <a class="text-white text-decoration-none btn btn-danger btn-lg w-25" href="{{ route('complaints.index') }}">Back</a>

                    @if ($complaint->status_id == 1)
                        <a class="text-white text-decoration-none btn btn-primary btn-lg w-25" href="{{ route('complaints.edit', ['complaint' => $complaint->id]) }}">Edit</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
