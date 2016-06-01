@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">設定</h1>
        </div>
    </div>
    @role(['admin', 'client'])
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">Email警報上限值</h3>
            </div>
        </div>
        @include('partials.forms.threshold-form')
    @endrole
</div>
@endsection
