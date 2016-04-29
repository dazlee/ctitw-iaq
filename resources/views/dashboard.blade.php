@extends('layouts.app')

@section('content')
<div class="page-wrapper" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">部門一</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="highchart-content" id="historychart" style="width: 100%; height: 500px;"></div>
        </div>
    </div>


</div>
@endsection

@section('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script data-main="js/dashboard" src="/js/requirejs/require.js"></script>
@endsection
