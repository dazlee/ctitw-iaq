<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <div class="col-sm-2 pt-6">
                <span class="label label-default f-m">選擇部門</span>
            </div>
            <div id="device-selector-multiple-stats-chart" class="col-sm-10">
                @foreach ($devices as $device)
                    <button class="btn btn-device btn-sm mr-5" data-device-id="{{$device->client->device_account . '-' . $device->index}}" data-device-name="{{$device->name}}">{{$device->name}}</button>
                @endforeach
            </div>
        </div>
        <div class="row mt-10">
            <div class="col-sm-2 pt-6">
                <span class="label label-default f-m">選擇資料種類</span>
            </div>
            <div id="datatype-selector-multiple-stats-chart" class="col-sm-10">
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
            <div id="daterange-multiple-stats-chart" class="col-sm-6 input-group input-daterange">
                <input type="text" class="form-control" name="from" value="<?php echo date_format($from, "m-d-Y"); ?>">
                <span class="input-group-addon">-</span>
                <input type="text" class="form-control" name="to" value="<?php echo date_format($to, "m-d-Y"); ?>">
            </div>
            <div class="col-sm-1">
                <button id="refresh-multiple-stats-chart" class="btn btn-success btn-sm">刷新</button>
            </div>
        </div>
        <div class="highchart-content" id="multiple-stats-chart" style="width: 100%; height: 500px;"></div>
    </div>
</div>
