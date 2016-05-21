@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">濟耀國際</h1>
        </div>
    </div>
    @role('admin')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            {{$list}}
        </div>
    </div>
    @include('partials.tables.' . $type .'-account-table')
    @endrole
</div>
@endsection
