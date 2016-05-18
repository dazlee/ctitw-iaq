@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard" id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $name }} 內容</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">修改{{$name}}</h3>
        </div>
    </div>
    @include('partials.forms.edit-' . $type . '-form')
</div>
@endsection

@section('scripts')

@endsection
