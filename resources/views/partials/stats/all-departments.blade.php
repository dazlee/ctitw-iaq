<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <div class="col-sm-2 pt-6">
                <span class="label label-default f-m">選擇時間範圍</span>
            </div>
            <div id="average-daterange-multiple-stats-table" class="col-sm-6 input-group input-daterange">
                <input type="text" class="form-control" name="from" value="<?php echo date_format($from, "m-d-Y"); ?>">
                <span class="input-group-addon">-</span>
                <input type="text" class="form-control" name="to" value="<?php echo date_format($to, "m-d-Y"); ?>">
            </div>
            <div class="col-sm-1">
                <button id="refresh-multiple-stats-table" class="btn btn-success btn-sm">刷新</button>
            </div>
        </div>
        <div class="row pt-6">
            <table id="multiple-stats-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>部門</th>
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

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">各部門圖表</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <div class="col-sm-2 pt-6">
                <span class="label label-default f-m">選擇部門</span>
            </div>
            <div id="device-selector" class="col-sm-10">
            </div>
        </div>
        <div class="row mt-10">
            <div class="col-sm-2 pt-6">
                <span class="label label-default f-m">選擇資料種類</span>
            </div>
            <div id="datatype-selector" class="col-sm-10">
                <ul class="nav nav-pills">
                  <li role="presentation" class="active btn-default"><a data-filter="co2" href="#">二氧化碳</a></li>
                  <li role="presentation"><a data-filter="temp" href="#">溫度</a></li>
                  <li role="presentation"><a data-filter="rh" href="#">濕度</a></li>
                </ul>
            </div>
        </div>
        <div class="row mt-10">
            <div class="col-sm-2 pt-6">
                <span class="label label-default f-m">選擇時間範圍</span>
            </div>
            <div id="average-daterange-multiple-stats-chart" class="col-sm-6 input-group input-daterange">
                <input type="text" class="form-control" name="from" value="<?php echo date_format($from, "m-d-Y"); ?>">
                <span class="input-group-addon">-</span>
                <input type="text" class="form-control" name="to" value="<?php echo date_format($to, "m-d-Y"); ?>">
            </div>
            <div class="col-sm-1">
                <button id="refresh-multiple-stats-chart" class="btn btn-success btn-sm">刷新</button>
            </div>
        </div>
        <div class="highchart-content" id="departmentchart" style="width: 100%; height: 500px;"></div>
    </div>
</div>

@section('scripts')
<script src="/client/lib/highcharts/highcharts.js"></script>
<script src="/client/lib/highcharts/modules/exporting.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/locales/bootstrap-datepicker.zh-TW.min.js"></script>
<scirpt src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"/>
<script data-main="/js/stats/all-departments.js" src="/client/lib/requirejs/require.js"></script>
@endsection
