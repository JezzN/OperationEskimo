@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-12">
            <h3>Loot Drops for {{ ucfirst($characterName) }}</h3>
            @include('loot._loot_drops_table')
        </div>
    </div>
@stop
