@extends('layouts.app')

@section('content')
<div class="page-wrapper" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">部門一</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-11 col-md-offset-1">
            <div class="row">
                <div class="col-sm-2 pt-6">
                    <span class="label label-default f-m">選擇時間範圍</span>
                </div>
                <div class="col-sm-6 input-group input-daterange">
                    <input type="text" class="form-control" value="2012-04-05">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control" value="2012-04-19">
                </div>
            </div>
            <div class="row pt-6">
                <div class="col-sm-2 pt-6">
                    <button id="refresh" class="btn btn-success btn-sm">刷新</button>
                    <button id="download" class="btn btn-primary btn-sm">下載</button>
                </div>
                <div id="unit-selector" class="col-sm-6 p-0 pt-6">
                    <ul class="nav nav-pills">
                      <li role="presentation" class="active btn-default"><a data-filter="hr" href="#">小時</a></li>
                      <li role="presentation"><a data-filter="8hrs" href="#">8小時</a></li>
                      <li role="presentation"><a data-filter="day" href="#">日</a></li>
                      <li role="presentation"><a data-filter="week" href="#">週</a></li>
                      <li role="presentation"><a data-filter="month" href="#">月</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div data-device-id="<?php echo $id;?>" class="highchart-content" id="historychart" style="width: 100%; height: 500px;"></div>
        </div>
    </div>


</div>
@endsection

@section('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/locales/bootstrap-datepicker.zh-TW.min.js"></script>
<scirpt src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"/>
<script data-main="/js/dashboard" src="/client/lib/requirejs/require.js"></script>
@endsection
