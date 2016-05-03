@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">部門一</h1>
        </div>
    </div>

    <div class="row mt-10">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                <div class="col-sm-2 pt-6">
                    <span class="label label-default f-m">選擇時間範圍</span>
                </div>
                <div class="col-sm-6 input-group input-daterange">
                    <input type="text" class="form-control" name="from" value="<?php echo date_format($from, "m-d-Y"); ?>">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control" name="to" value="<?php echo date_format($to, "m-d-Y"); ?>">
                </div>
                <div class="col-sm-1">
                    <button id="refreshTable" class="btn btn-success btn-sm">刷新</button>
                </div>
            </div>
            <div class="row pt-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>設備</th>
                            <th colspan="3">二氧化碳</th>
                            <th colspan="3">溫度</th>
                            <th colspan="3">濕度</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>最大值</th>
                            <th>最小值</th>
                            <th>平均值</th>
                            <th>最大值</th>
                            <th>最小值</th>
                            <th>平均值</th>
                            <th>最大值</th>
                            <th>最小值</th>
                            <th>平均值</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>A001-1</th>
                            <td><span class="fg-red">1124</span></td>
                            <td>1234</td>
                            <td>1234</td>
                            <td>1124</td>
                            <td>1234</td>
                            <td>1234</td>
                            <td>1124</td>
                            <td>1234</td>
                            <td>1234</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-10">
        <div class="col-md-11 col-md-offset-1">
            <div class="row">
                <div class="col-sm-2 pt-6">
                    <span class="label label-default f-m">選擇時間範圍</span>
                </div>
                <div class="col-sm-6 input-group input-daterange">
                    <input type="text" class="form-control" name="from" value="<?php echo date_format($from, "m-d-Y"); ?>">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control" name="to" value="<?php echo date_format($to, "m-d-Y"); ?>">
                </div>
            </div>
            <div class="row pt-6">
                <div class="col-sm-2 pt-6">
                    <button id="refreshHistory" class="btn btn-success btn-sm">刷新</button>
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
