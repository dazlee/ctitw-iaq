@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            @if (Auth::guest())
                <h1 class="page-header">濟耀國際</h1>
            @else
                <h1 class="page-header">{{ Auth::user()->name }}</h1>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            歡迎回來
        </div>
    </div>
    @role('admin')
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">{{$list}}</h3>
            </div>
        </div>
        @include('partials.tables.' . $type .'-account-table')
    @endrole
</div>
@endsection
