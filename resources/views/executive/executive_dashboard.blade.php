@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-center m-3">Assigned Task</h2>

    <div class="row">
        {{-- foreach all the status--}}
        @foreach ($status_verified_complaint as $sts)
            @include('components.status_card', ['route' => 'executive.verified_complaints.index'])
        @endforeach

    </div>
</div>

@endsection
