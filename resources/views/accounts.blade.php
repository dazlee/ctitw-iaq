@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">帳號管理</h1>
        </div>
    </div>

    @include('partials.agent-form')
    @include('partials.client-form')
    @include('partials.department-form')

</div>
@endsection

@section('scripts')
<script src="/client/lib/highcharts/highcharts.js"></script>
<script src="/client/lib/highcharts/modules/exporting.js"></script>

@endsection
