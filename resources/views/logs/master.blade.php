@extends('master')

@section('content')
    <div class="row" style="margin-top: 10px;">
        <ul class="nav nav-tabs">
            <li role="presentation" class="{{ Route::current()->getName() === 'logs' || Route::current()->getName('logs.character') ? 'active' : '' }}"><a href="{{ route('logs') }}">Latest Rankings</a></li>
            <li role="presentation"><a href="http://logs.operation-eskimo.com">Go to WarcraftLogs</a></li>
        </ul>
    </div>


    @yield('loot-content')
@stop
