@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-3">Complaint List</h2>

        <form id="search_filter_form" class="mb-3" action="{{ route('complaints.index') }}" method="GET">
            <div class="d-md-flex d-block justify-content-between">
                <div class="col-md-5 col-12 mb-md-0 mb-2">
                    <div class="input-group rounded">
                        <input type="text" name="search" value="{{ request()->query('search') }}" class="form-control rounded" placeholder="Search Title"
                            aria-label="Search" aria-describedby="search-addon" />
                        <span class="input-group-text border-0" id="search-addon">
                            <input class="btn btn-info" type="submit" value="Submit">
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
        <div class="d-flex justify-content-end mb-3">
            <a class="btn btn-danger btn-lg mx-2" href="{{ route('home') }}">Back</a>
            <a class="btn btn-primary btn-lg" href="{{ route('complaints.create') }}">New</a>
        </div>
        @include('components.action_message')
        <table id="studentComplaintTable" class="table table-bordered table-striped">
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
                @if ($complaints->count() > 0)
                    @foreach ($complaints as $complaint)
                        <tr>
                            <th class="fw-bold d-none d-sm-table-cell" scope="row">{{ $complaint->id }}</th>
                            <td>{{ $complaint->title }}</td>
                            <td class="d-none d-sm-table-cell">{{ $complaint->created_at->format('d M Y') }}</td>
                            <td>{{ $complaint->status->name }}</td>
                            <td>
                                <div class="d-inline-flex w-100 justify-content-around">
                                    {{-- zi keong if the status is not pending then user cannot be edit and delete --}}

                                    <a href="{{ route('complaints.show', ['complaint' => $complaint->id]) }}"><i class="fa-solid fa-file-lines view_icon me-2"></i></a>


                                    @if ($complaint->status_id == 1)
                                        <a href="{{ route('complaints.edit', ['complaint' => $complaint->id]) }}"><i class="fa-solid fa-pen-to-square edit_icon"></i></a>

                                        <form action="{{ route('complaints.destroy', ['complaint' => $complaint->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="image" src="{{ asset('images/delete.png') }}" alt="delete.png" width="30px">
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @elseif ($complaints->count() == 0 && $total_complaints > 0)
                    <tr>
                        <td colspan="5">No record.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        @if ($total_complaints == 0)
            <div class="alert btn-info-user-list alert-dismissible fade show" role="alert">
                <strong>You haven't create any complaint before.</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{ $complaints->links() }}
    </div>
@endsection
