@extends('logs.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-12">
            <h3>Latest Rankings</h3>

           @include('logs.ranking_table')

        </div>
    </div>
@stop
