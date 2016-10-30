@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-12">
            <h3>Mythic+ Items Looted</h3>
            <p>Data collection began on {{ $start->loot_time }}, any activity before that will not be accounted for.</p>
            <table class="table table-striped"   >
                <tr>
                    <th>Player</th>
                    <th>This Week</th>
                    <th>This Month</th>
                </tr>
                @foreach( $mythicPlusLooters as $stat )
                    <tr>
                        <td>{{ $stat['name']  }}</td>
                        <td>{{ $stat['this_week']  }}</td>
                        <td>{{ $stat['this_month']  }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@stop