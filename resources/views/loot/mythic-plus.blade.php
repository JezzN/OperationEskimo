@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-4">
            <h3>Mythic+ This Reset</h3>
            <table class="table table-striped"   >
                <tr>
                    <th>Player</th>
                    <th>Total</th>
                </tr>
                @foreach( $completionsThisReset as $player => $activity )
                    <tr>
                        <td>{{ $player  }}</td>
                        <td>{{ $activity['total']  }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="col-md-4">
            <h3>Mythic+ This Month</h3>
            <table class="table table-striped"   >
                <tr>
                    <th>Player</th>
                    <th>Total</th>
                </tr>
                @foreach( $completionsThisMonth as $player => $activity )
                    <tr>
                        <td>{{ $player  }}</td>
                        <td>{{ $activity['total']  }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="col-md-4">
            <h3>Mythic+ All Time</h3>
            <table class="table table-striped"   >
                <tr>
                    <th>Player</th>
                    <th>Total</th>
                </tr>
                @foreach( $completionsAllTime as $player => $activity )
                    <tr>
                        <td>{{ $player  }}</td>
                        <td>{{ $activity['total']  }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@stop