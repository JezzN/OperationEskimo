@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-12">
            <h3>Raid Loot (Mythic Only)</h3>
            @include('loot._loot_drops_table')
        </div>
    </div>
@stop
