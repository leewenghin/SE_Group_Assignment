@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="text-center m-3">Own Complaint Analysis</h2>

    <div class="row">
        {{-- foreach all the status--}}
        @foreach ($status as $sts)
            @include('components.status_card', ['route' => 'complaints.index'])
        @endforeach

    </div>
</div>

@endsection
