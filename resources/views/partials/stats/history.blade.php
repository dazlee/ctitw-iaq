@include('partials.dashboards.single-stats-table')

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">歷史圖表</h3>
    </div>
</div>
@include('partials.dashboards.single-stats-chart')

@section('scripts')
<script src="/client/lib/highcharts/highcharts.js"></script>
<script src="/client/lib/highcharts/modules/exporting.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/locales/bootstrap-datepicker.zh-TW.min.js"></script>
<scirpt src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"/>
<script data-main="/js/stats/history.js" src="/client/lib/requirejs/require.js"></script>
@endsection
