@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-7 col-lg-8">

            <div class="d-flex justify-content-between mb-3">
                <h3 class="mb-3 text-center">Complaint Detail</h3>
                <div class="btn btn-lg text-bg-warning">
                    Pending
                </div>
            </div>

            <?php
            $collapse = false;
            if ($complaints->count() > 1) {
                $collapse = true;
            }
            $i = 0;
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

            <form class="mt-3" action="{{ route('helpdesk.verified_complaints.store') }}" method="POST">
                @csrf
                @foreach ($complaints as $complaint)
                    <input type="hidden" name="groupComplaint[]" value="{{ $complaint->id }}" />
                @endforeach
                <h3>Helpdesk Operation</h3>
                <div class="row">
                    <div class="col-12">
                        <label for="commonTitle" class="form-label">Common Title</label>
                        <input type="text" class="form-control @error('common_title') is-invalid @enderror" id="commonTitle" name="common_title" value="{{ request()->input('common_title') }}" placeholder="" required>
                        <div class="invalid-feedback">
                            @error('common_title')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-lg-flex justify-content-between d-block">
                    <div class="col-lg-6 col-12 m-auto">
                        <div class="form-check fs-5 standard_content">
                            <input class="form-check-input is-need-department" type="radio" value="1" id="acceptAction" name="action" required @if('1' == old('action')) checked @endif>
                            <label class="form-check-label" for="acceptAction">
                                Accept
                            </label>
                        </div>
                        <div class="form-check fs-5 standard_content">
                            <input class="form-check-input is-need-department @error('action') is-invalid @enderror" type="radio" value="0" id="declineAction" name="action" required @if('0' == old('action')) checked @endif>
                            <label class="form-check-label" for="declineAction">
                                Decline
                            </label>
                            <div class="invalid-feedback">
                                @error('action')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-12 mt-3">
                            <label for="select_department" class="form-label">Assign to:</label>
                            <select class="form-select @error('department') is-invalid @enderror" id="select_department" name="department">
                                <option value="">Choose a department...</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ $department->id == old('department') ? 'selected': '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                @error('department')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 my-3">
                        <label for="executive_remark" class="form-label">Remark</label>
                        <textarea class="form-control @error('remark') is-invalid @enderror" name="remark" id="executive_remark" cols="15" rows="5" required>{{ old('remark') }}</textarea>
                        <div class="invalid-feedback">
                            @error('remark')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-around mt-5 row">
                    <a class="text-white text-decoration-none btn btn-danger btn-lg w-25" href="javascript:void(0)" onclick="history.go(-1)">
                        Back
                    </a>
                    <button class="btn btn-primary btn-lg w-25" type="submit">Save</button>
                </div>
            </form>

        </div>
    </div>

@endsection

