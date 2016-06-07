@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">年度統計下載</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-offset-1">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>名稱</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($downloads as $download)
                        <tr>
                            <td>{{$download['name']}}</td>
                            <td><a class="btn btn-link" href="/stats-files/{{$download['path']}}">下載</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
