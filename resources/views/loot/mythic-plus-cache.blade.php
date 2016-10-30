@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-6">
            <h3>This Week's Mythic+ Cache Rewards</h3>
            <table class="table table-striped"   >
                <tr>
                    <th>Player</th>
                    <th>Cache Level</th>
                    <th>Item</th>
                </tr>
                @foreach( $cacheItems as $item )
                    <tr>
                        <td>{{ $item->character_name  }}</td>
                        <td>{{ $item->getCacheLevel()  }}</td>
                        <td>{!!  $item->tooltip()  !!}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="col-md-6">
            <h3>No Cache Found</h3>
            <table class="table table-striped">
                @foreach( $raidersWithoutCache as $raider )
                    <tr>
                        <td>{{ $raider  }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@stop
