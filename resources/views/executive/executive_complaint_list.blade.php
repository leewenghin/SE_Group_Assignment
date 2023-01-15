@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-3">Task List</h2>
        <form id="search_filter_form" class="mb-3" action="{{ route('executive.verified_complaints.index') }}" method="GET">
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
                            @if (!($status->id == 1))
                                <option value="{{ $status->id }}" {{ ($status->id == request()->query('status_filter')) ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <table id="execuativeComplaintTable" class="table table-bordered table-striped table table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="d-none d-sm-table-cell">No</th>
                    <th scope="col">Title</th>
                    <th scope="col" class="d-none d-sm-table-cell">Create Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider table-secondary">
                @if ($verified_complaints->count() > 0)
                    @foreach ($verified_complaints as $verified_complaint)
                        <tr>
                            <th class="fw-bold d-none d-sm-table-cell" scope="row">{{ $verified_complaint->id }}</th>
                            <td>{{ $verified_complaint->common_title }}</td>
                            <td class="d-none d-sm-table-cell">{{ $verified_complaint->created_at->format('d M Y') }}</td>
                            <td>{{ $verified_complaint->status->name }}</td>
                            <td>
                                <div class="d-inline-flex w-100 justify-content-around">
                                    <a href="{{ route('executive.verified_complaints.show', ['verified_complaint' => $verified_complaint->id]) }}"><i class="fa-solid fa-file-lines view_icon me-2"></i></a>

                                    @if ($verified_complaint->status_id == 5 || ($verified_complaint->status_id == 2 && $verified_complaint->complaint_action_id != 4))
                                        <a href="{{ route('executive.verified_complaints.show', ['verified_complaint' => $verified_complaint->id]) }}#operation"><i class="fa-solid fa-handshake-simple accept_decline_icon me-2"></i></a>
                                    @elseif ($verified_complaint->status_id == 3)
                                        <a href="{{ route('executive.verified_complaints.show', ['verified_complaint' => $verified_complaint->id]) }}#operation"><i class="fa-solid fa-pen-to-square edit_icon"></i></a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @elseif ($verified_complaints->count() == 0 && $total_verified_complaints > 0)
                    <tr>
                        <td colspan="5">No record.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if ($total_verified_complaints == 0)
            <div class="alert btn-info-user-list alert-dismissible fade show" role="alert">
                <strong>There are not any assigned tasks yet.</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{ $verified_complaints->links() }}

        <div class="d-flex justify-content-end">
            <a class="btn btn-danger btn-lg mx-2" href="{{ route('executive.dashboard') }}">Back</a>
        </div>
    </div>
@endsection
