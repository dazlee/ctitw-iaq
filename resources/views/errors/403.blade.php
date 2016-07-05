@extends('layouts.login')

@section('content')
<style>
    .container {
        text-align: center;
        display: table-cell;
        vertical-align: middle;
    }

    .title {
        font-size: 72px;
        margin-bottom: 40px;
        color: #B0BEC5;
        font-weight: 100;
        font-family: 'Lato';
        margin-top: 100px;
    }
</style>
<div class="container forbidden login">
    <div class="row">
        <div class="col-lg-12">
            <div class="title">請先登入.</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
