@extends('layouts.app')

@section('content')
<div class="page-wrapper dashboard stats" id="page-wrapper" data-device-account="<?php echo $deviceAccount;?>">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">整體環境 - {{ $name }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">{{ $name }}</h3>
        </div>
    </div>
    @include('partials.stats.' . $type)

</div>
@endsection
