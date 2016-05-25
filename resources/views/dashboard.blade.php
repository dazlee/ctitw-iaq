@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper" data-device-id="<?php echo $deviceId;?>" >
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{$deviceId}}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">即時統計</h3>
        </div>
    </div>
    @include('partials.dashboards.realtime-info-panel')
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">即時圖表</h3>
        </div>
    </div>
    @include('partials.dashboards.realtime-info-chart')

    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">歷史資訊</h3>
        </div>
    </div>
    @include('partials.dashboards.single-stats-table')
    @include('partials.dashboards.single-stats-chart')

</div>
@endsection

@section('scripts')
<script src="/client/lib/highcharts/highcharts.js"></script>
<script src="/client/lib/highcharts/modules/exporting.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/locales/bootstrap-datepicker.zh-TW.min.js"></script>
<scirpt src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"/>
<script data-main="/js/dashboard" src="/client/lib/requirejs/require.js"></script>
@endsection
