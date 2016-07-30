<div class="row mt-10">
    <div class="col-md-12">
        <div class="col-sm-4">
            <div id="realtime-info-co2-panel" class="panel number-panel horizontal-panel panel-primary" data-co2-threshold="{{$threshold->co2}}">
                <div class="panel-heading">
                    <i class="fa fa-cloud"></i>
                </div>
                <div class="panel-body">
                    <div class="content">
                        <div class="title">二氧化碳</div>
                        <div class="value"><span class="number">0</span> ppm</div>
                    </div>
                    <div class="footer">警示值 {{$threshold->co2}} ppm</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div id="realtime-info-temp-panel" class="panel number-panel horizontal-panel panel-yellow" data-temp-threshold="{{$threshold->temp}}">
                <div class="panel-heading">
                    <i class="fa fa-sun-o"></i>
                </div>
                <div class="panel-body">
                    <div class="content">
                        <div class="title">溫度</div>
                        <div class="value"><span class="number">0</span> °C</div>
                    </div>
                    <div class="footer">警示值 {{$threshold->temp}} °C</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div id="realtime-info-rh-panel" class="panel number-panel horizontal-panel panel-green"  data-rh-threshold="{{$threshold->rh}}">
                <div class="panel-heading">
                    <i class="fa fa-tint"></i>
                </div>
                <div class="panel-body">
                    <div class="content">
                        <div class="title">濕度</div>
                        <div class="value"><span class="number">0</span> %</div>
                    </div>
                    <div class="footer">警示值 {{$threshold->rh}} %</div>
                </div>
            </div>
        </div>
    </div>
</div>
