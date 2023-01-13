@extends('layouts.app')

@section('content')

<div class="container">


    <h2 class="text-center m-3">Ongoing Complaints</h2>

    <div class="row">
        {{-- foreach all the status--}}
        @foreach ($status_verified_complaint as $sts)
            @include('components.status_card', ['route' => 'helpdesk.verified_complaints.index'])
        @endforeach

    </div>

    <h2 class="text-center m-3">All Complaints</h2>

    <div class="row">
        {{-- foreach all the status--}}
        @foreach ($status_complaint as $sts)
            @include('components.status_card', ['route' => 'helpdesk.complaints.index'])
        @endforeach

    </div>


</div>

@endsection
