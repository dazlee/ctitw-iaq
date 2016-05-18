@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $name }}管理</h1>
        </div>
    </div>
    @include('partials.forms.' . $type . '-form')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $name }} 列表</h1>
        </div>
    </div>
    @include('partials.tables.' . $type . '-account-table')
</div>
@endsection

@section('scripts')

@endsection
