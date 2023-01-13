@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-7 col-lg-8">

            @if ($verified_complaint->status_id == 6)
                <div class="mt-3 mb-5 d-lg-flex justify-content-between">
                    <div class="col-lg-6 col-12">
                        <h3>Report remark:</h3>
                        <div class="fs-5 text-bg-light p-3 rounded">
                            {{ $verified_complaint->finalize_remark }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between  mb-3">
                <h3 class="mb-3 text-center">Complaint Detail</h3>
                <div class="btn btn-lg text-bg-{{ $verified_complaint->status->GetColor() }}">
                    {{ $verified_complaint->status->name }}
                </div>
            </div>

            <?php
            if ($verified_complaint->complaints->count() > 1) {
                $collapse = true;
            }
            else {
                $collapse = false;
            }
            $i = 0;
            $complaints = $verified_complaint->complaints;
            ?>

            @foreach ($complaints as $complaint)
                <?php $i++; ?>
                @if ($collapse)
                    @include('components.collapse_complaint')
                @else
                    @include('components.collapse_complaint_form_content')
                @endif
            @endforeach

            <hr class="hr" />

            @include('components.complaint_history')

            <hr class="hr" />
            @if (in_array($verified_complaint->complaint_action_id, [1,3]))
                <form id="operation" class="mt-3" action="{{ route('executive.verified_complaints.action', ['verified_complaint' => $verified_complaint->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h3>Executive Operation</h3>
                    @if ($verified_complaint->complaint_action_id == 1)
                        <div class="d-lg-flex justify-content-between d-block">
                            <div class="col-lg-6 col-12 m-auto">
                                <div class="form-check fs-5 standard_content">
                                    <input class="form-check-input" type="radio" value="1" id="acceptAction" name="action" @if('1' == old('action')) checked @endif>
                                    <label class="form-check-label" for="acceptAction">
                                        Accept
                                    </label>
                                </div>
                                <div class="form-check fs-5 standard_content">
                                    <input class="form-check-input @error('action') is-invalid @enderror" type="radio" value="0" id="declineAction" name="action" @if('0' == old('action')) checked @endif>
                                    <label class="form-check-label" for="declineAction">
                                        Decline
                                    </label>
                                    <div class="invalid-feedback">
                                        @error('action')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12 my-3">
                                <label for="executive_remark" class="form-label">Remark</label>
                                <textarea class="form-control @error('remark') is-invalid @enderror" name="remark" id="executive_remark" cols="15" rows="5">{{ old('remark') }}</textarea>
                                <div class="invalid-feedback">
                                    @error('remark')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="file_evidence" class="form-label d-none">Provide an evidence</label>
                            <input id="executive_upload_file_evidence" class="form-control hidden d-none @error('file') is-invalid @enderror" type="file" name="file">
                            <div class="invalid-feedback">
                                @error('file')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    @elseif ($verified_complaint->complaint_action_id == 3)
                        <div class="col-lg-6 col-12 my-3">
                            <label for="executive_remark" class="form-label">Remark</label>
                            <textarea class="form-control @error('remark') is-invalid @enderror" name="remark" id="executive_remark" cols="15" rows="5">{{ old('remark') }}</textarea>
                            <div class="invalid-feedback">
                                @error('remark')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="file_evidence" class="form-label">Provide an evidence</label>
                            <input id="executive_upload_file_evidence" class="form-control hidden @error('file') is-invalid @enderror" type="file" name="file">
                        </div>
                    @endif

                    <div class="d-flex justify-content-around mt-5 row">
                        <button class="btn btn-primary btn-lg w-25" type="submit">Save</button>
                    </div>
                </form>
            @endif


            <div class="d-flex justify-content-around mt-5 row">
                <a class="text-white text-decoration-none btn btn-danger btn-lg w-25" href="{{ route('executive.verified_complaints.index') }}">
                    Back
                </a>
            </div>
            <!-- Button trigger modal -->
            <button id="declineTaskRecivedModelBtn" type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#declineTaskRecivedModel">
                Launch demo modal
            </button>
            <button id="fileUploadWarningModelBtn" type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#fileUploadWarningModel">
                Launch demo modal
            </button>
        </div>
    </div>
    @include("components.decline_task_received_model")
    @include("components.file_upload_warning_model")
@endsection
