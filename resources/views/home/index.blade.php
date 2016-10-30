@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('home._recruitment')
        </div>

        <div class="col-md-9">
            @include('home._news')
        </div>
    </div>
@stop
