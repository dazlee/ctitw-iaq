<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <div class="col-sm-2 pt-6">
                <span class="label label-default f-m">選擇時間範圍</span>
            </div>
            <div id="average-daterange-single-stats-table" class="col-sm-6 input-group input-daterange">
                <input type="text" class="form-control" name="from" value="<?php echo date_format($from, "m-d-Y"); ?>">
                <span class="input-group-addon">-</span>
                <input type="text" class="form-control" name="to" value="<?php echo date_format($to, "m-d-Y"); ?>">
            </div>
            <div class="col-sm-1">
                <button id="refresh-single-stats-table" class="btn btn-success btn-sm">刷新</button>
            </div>
        </div>
        <div class="row pt-6">
            <table id="single-stats-table" class="table table-bordered">
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
                        <th class="device-id"></th>
                        <td class="co2-max"></td>
                        <td class="co2-min"></td>
                        <td class="co2-avg"></td>
                        <td class="temp-max"></td>
                        <td class="temp-min"></td>
                        <td class="temp-avg"></td>
                        <td class="rh-max"></td>
                        <td class="rh-min"></td>
                        <td class="rh-avg"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
