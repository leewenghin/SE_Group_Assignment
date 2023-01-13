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
            @if (in_array($verified_complaint->complaint_action_id, [1,4,5]))
                <form id="operation" class="mt-3" action="{{ route('helpdesk.verified_complaints.action', ['verified_complaint' => $verified_complaint->id]) }}" method="POST">
                    @csrf
                    <h3>Helpdesk Operation</h3>

                    <div class="row">
                        <div class="col-12">
                            <label for="commonTitle" class="form-label">Common Title</label>
                            <input type="text" class="form-control" id="commonTitle" name="common_title" value="{{ $verified_complaint->common_title }}" placeholder="" disabled>
                        </div>
                    </div>
                    <?php
                        $is_not_yet_accept_complaint = ($verified_complaint->complaint_action_id == 1);
                        $accept_or_approve = $is_not_yet_accept_complaint ? 'Accept' : 'Approve';
                        $decline_or_reject = $is_not_yet_accept_complaint ? 'Decline' : 'Reject';
                    ?>
                    <div class="d-lg-flex justify-content-between d-block">
                        <div class="col-lg-6 col-12 m-auto">
                            <div class="form-check fs-5 standard_content">

                                @if ($is_not_yet_accept_complaint)
                                    <input class="form-check-input" type="radio" value="1" id="acceptAction" name="action" checked disabled />
                                @else
                                    <input class="form-check-input @error('action') is-invalid @enderror" type="radio" value="1" id="acceptAction" name="action" @if('1' == old('action')) checked @endif />
                                @endif

                                <label class="form-check-label" for="acceptAction">
                                    {{ $accept_or_approve }}
                                </label>
                            </div>
                            <div class="form-check fs-5 standard_content">

                                @if ($is_not_yet_accept_complaint)
                                    <input class="form-check-input" type="radio" value="0" id="declineAction" name="action" disabled />
                                @else
                                    <input class="form-check-input @error('action') is-invalid @enderror" type="radio" value="0" id="declineAction" name="action" @if('0' == old('action')) checked @endif />
                                @endif

                                <label class="form-check-label" for="declineAction">
                                    {{ $decline_or_reject }}
                                </label>
                                <div class="invalid-feedback">
                                    @error('action')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6 col-12 mt-3">
                                <label for="select_department" class="form-label">Assign to:</label>
                                <select class="form-select @error('department') is-invalid @enderror" id="select_department" name="department" @if($is_not_yet_accept_complaint) onchange="this.form.submit();" @endif>
                                    <option value="">Choose a department...</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ $department->id == old('department', $verified_complaint->assigned_to_department_id) ? 'selected': '' }}>{{ $department->name }}</option>
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

                            @if ($is_not_yet_accept_complaint)
                                <textarea class="form-control" name="remark" id="executive_remark" cols="15" rows="5" disabled>{{ $verified_complaint->description }}</textarea>
                            @else
                                <textarea class="form-control @error('remark') is-invalid @enderror" name="remark" id="executive_remark" cols="15" rows="5">{{ old('remark') }}</textarea>
                            @endif

                            <div class="invalid-feedback">
                                @error('remark')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    @if (!$is_not_yet_accept_complaint)
                        <div class="d-flex justify-content-around mt-5 row">
                            <button class="btn btn-primary btn-lg w-25" type="submit">Save</button>
                        </div>
                    @endif
                </form>
            @endif
            <div class="d-flex justify-content-around mt-5 row">
                <a class="text-white text-decoration-none btn btn-danger btn-lg w-25" href="{{ route('helpdesk.verified_complaints.index') }}">
                    Back
                </a>
            </div>
        </div>
    </div>

@endsection

