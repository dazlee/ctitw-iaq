@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">管理計劃書存檔</h1>
        </div>
    </div>
    @role('client')
        @include('partials.forms.file-form')
    @endrole
    @include('partials.tables.file-table')
</div>
@endsection
