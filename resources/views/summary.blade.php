@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">整體環境 - 即時資訊</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">即時統計</h3>
        </div>
    </div>
    <div class="row mt-10">
        <div class="col-md-12">
            <div class="col-sm-4">
                <div class="panel number-panel horizontal-panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-cloud"></i>
                    </div>
                    <div class="panel-body">
                        <div class="content">
                            <div class="title">二氧化碳</div>
                            <div class="value fg-red">500 ppm</div>
                        </div>
                        <div class="footer">法定均值 690 ppm</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel number-panel horizontal-panel panel-yellow">
                    <div class="panel-heading">
                        <i class="fa fa-sun-o"></i>
                    </div>
                    <div class="panel-body">
                        <div class="content">
                            <div class="title">溫度</div>
                            <div class="value">25.5 C</div>
                        </div>
                        <div class="footer">法定均值 28.4c</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel number-panel horizontal-panel panel-green">
                    <div class="panel-heading">
                        <i class="fa fa-tint"></i>
                    </div>
                    <div class="panel-body">
                        <div class="content">
                            <div class="title">濕度</div>
                            <div class="value">65%</div>
                        </div>
                        <div class="footer">法定均值 70%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">即時圖表</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="highchart-content" id="summraychart" style="width: 100%; height: 500px;"></div>
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

@endsection
