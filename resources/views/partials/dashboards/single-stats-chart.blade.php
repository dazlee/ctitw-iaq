<div class="row mt-20">
    <div class="col-md-11 col-md-offset-1">
        <div class="row">
            <div class="col-sm-2 pt-6">
                <span class="label label-default f-m">選擇時間範圍</span>
            </div>
            <div id="daterange-single-stats-chart" class="col-sm-6 input-group input-daterange">
                <input type="text" class="form-control" name="from" value="<?php echo date_format($from, "m-d-Y"); ?>">
                <span class="input-group-addon">-</span>
                <input type="text" class="form-control" name="to" value="<?php echo date_format($to, "m-d-Y"); ?>">
            </div>
        </div>
        <div class="row pt-6">
            <div class="col-sm-2 pt-6">
                <button id="refresh-single-stats-chart" class="btn btn-success btn-sm">刷新</button>
            </div>
            <div id="unit-selector-single-stats-chart" class="col-sm-6 p-0 pt-6">
                <ul class="nav nav-pills">
                  <li role="presentation"><a data-filter="10mins" href="#">10分鐘</a></li>
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
        <div class="highchart-content" id="single-stats-chart" style="width: 100%; height: 500px;"></div>
    </div>
</div>
