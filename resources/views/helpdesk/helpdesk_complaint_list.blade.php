@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-3">Complaint List</h2>
        <form id="search_filter_form" class="mb-3" action="{{ route('helpdesk.complaints.index') }}" method="GET">
            <div class="d-md-flex d-block justify-content-between">
                <div class="col-md-5 col-12 mb-md-0 mb-2">
                    <div class="input-group rounded">
                        <input type="text" name="search" value="{{ request()->query('search') }}" class="form-control rounded" placeholder="Search Title"
                            aria-label="Search" aria-describedby="search-addon" />
                        <span class="input-group-text border-0" id="search-addon">
                            <input class="btn btn-info" type="submit">
                        </span>
                    </div>
                </div>
                <div class="col-md-5 col-12">
                    <select class="form-select" id="status_filter" name="status_filter">
                        <option value="">Choose a status...</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ ($status->id == request()->query('status_filter')) ? 'selected' : '' }}>{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @include('components.action_message')
        <input type="hidden" id="create_new_verified_complaint" name="url" value="{{ route('helpdesk.verified_complaints.create') }}"/>
        <input type="hidden" id="add_complaint_to_existing" name="url" value="{{ route('helpdesk.verified_complaints.add_complaint') }}"/>
        <form id="form" action="{{ route('helpdesk.verified_complaints.create') }}" method="POST">
            @csrf
            <table id="execuativeComplaintTable" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="d-none d-sm-table-cell">No</th>
                        <th scope="col">Title</th>
                        <th scope="col" class="d-none d-sm-table-cell">Create Date</th>
                        <th scope="col" class="d-none d-sm-table-cell">Status</th>
                        <th scope="col">Action</th>
                        <th scope="col">Group</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider table-secondary">
                    {{-- zi keong - loop all the content base on the database, --}}
                    @if ($complaints->count() > 0)
                        @foreach ($complaints as $complaint)
                            <tr>
                                <th class="fw-bold d-none d-sm-table-cell" scope="row">{{ $complaint->id }}</th>
                                <td>{{ $complaint->title }}</td>
                                <td class="d-none d-sm-table-cell">{{ $complaint->created_at->format('d M Y') }}</td>
                                <td class="d-none d-sm-table-cell">{{ $complaint->status->name }}</td>
                                <td>
                                    <div class="d-inline-flex w-100 justify-content-around">
                                        <a href="{{ route('helpdesk.complaints.show', ['complaint' => $complaint->id, 'search' => request()->query('search'), 'status_filter' => request()->query('status_filter')]) }}"><i class="fa-solid fa-eye view_icon me-2"></i></a>
                                        @if ($complaint->status_id != 1 && $complaint->verified_complaint_id != null)
                                            <a href="{{ route('helpdesk.verified_complaints.show', ['verified_complaint' => $complaint->verified_complaint_id]) }}"><i class="fa-solid fa-file-lines view_icon me-2"></i></a>
                                            @if ($complaint->status_id == 5)
                                                <a href="{{ route('helpdesk.verified_complaints.show', ['verified_complaint' => $complaint->verified_complaint_id]) }}#operation"><i class="fa-solid fa-people-roof assign_icon"></i></a>
                                            @elseif ($complaint->status_id == 4)
                                                <a href="{{ route('helpdesk.verified_complaints.show', ['verified_complaint' => $complaint->verified_complaint_id]) }}#operation"><i class="fa-solid fa-pen-to-square edit_icon"></i></a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input m-auto group_checkbox" type="checkbox"
                                            value="{{ $complaint->id }}" id="groupComplaint" name="groupComplaint[]"
                                            {{ ($complaint->status_id == 1) ? '' : 'disabled' }}
                                            />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @elseif ($complaints->count() == 0 && $total_complaints > 0)
                        <tr>
                            <td colspan="6">No record.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="d-flex justify-content-end my-3 me-1 row">
                <button class="btn btn-danger w-25 me-3" type="button" onclick="openGroupToExisting()">Existing Group</button>
                <button class="btn btn-warning w-25" type="button" onclick="openCommonTitle()">Group</button>
            </div>

            <!-- Button trigger modal -->
            <button id="pupUpCommonTitleModelBtn" type="button" class="d-none" data-bs-toggle="modal"
                data-bs-target="#popUpCommonTitleModal">
            </button>

            @include('components.pop_up_common_title_model')

        </form>

        @if ($total_complaints == 0)
            <div class="alert btn-info-user-list alert-dismissible fade show" role="alert">
                <strong>There are not incoming complaints yet.</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{ $complaints->links() }}

        <div class="d-flex justify-content-end">
            <a class="btn btn-danger btn-lg mx-2" href="{{ route('helpdesk.dashboard') }}">Back</a>
        </div>
    </div>



    <!-- Button trigger modal -->
    <button id="noticeToCheckAtLeastOneModelBtn" type="button" class="d-none" data-bs-toggle="modal"
        data-bs-target="#noticeToCheckAtLeastOneModel">
    </button>

    @include('components.notice_to_checked_at_least_one_model')
@endsection


