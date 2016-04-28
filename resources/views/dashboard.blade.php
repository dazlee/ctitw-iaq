@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Multiple Axes Line Chart Example
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="flot-chart">
                        <div class="flot-chart-content" id="flot-line-chart" style="width: 500px; height: 300px;"></div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
    </div>


</div>
@endsection
