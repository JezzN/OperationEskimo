@extends('master')

@section('content')
    <div class="row" style="margin-top: 10px;">
        <ul class="nav nav-tabs">
            <li role="presentation" class="{{ Route::current()->getName() === 'artifacts.index' ? 'active' : '' }}"><a href="{{ route('artifacts.index') }}">Character Progress</a></li>
            <li role="presentation" class="{{ Route::current()->getName() === 'loot' ||  Route::current()->getName() === 'loot.character' ? 'active' : '' }}"><a href="{{ route('loot') }}">Loot Drops</a></li>
            <li role="presentation" class="{{ Route::current()->getName() === 'loot.raid' ? 'active' : '' }}"><a href="{{ route('loot.raid') }}">Raid Loot</a></li>
            <li role="presentation" class="{{ Route::current()->getName() === 'loot.mythic-plus' ? 'active' : '' }}"><a href="{{ route('loot.mythic-plus') }}">Mythic+ Activity</a></li>
            <li role="presentation" class="{{ Route::current()->getName() === 'loot.mythic-plus-cache' ? 'active' : '' }}"><a href="{{ route('loot.mythic-plus-cache') }}">Mythic+ Weekly Cache</a></li>
        </ul>
    </div>


    @yield('loot-content')
@stop
