@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-6">
            <h3>Mythic+ This Reset</h3>
            <table class="table table-striped"   >
                <tr>
                    <th>Player</th>
                    <th>2+</th>
                    <th>5+</th>
                    <th>10+</th>
                    <th>15+</th>
                    <th>Total</th>
                </tr>
                @foreach( $completionsThisReset as $player => $activity )
                    <tr>
                        <td>{{ $player  }}</td>
                        <td>{{ $activity['plus_2']  }}</td>
                        <td>{{ $activity['plus_5']  }}</td>
                        <td>{{ $activity['plus_10']  }}</td>
                        <td>{{ $activity['plus_15']  }}</td>
                        <td>{{ $activity['total']  }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="col-md-6">
            <h3>Mythic+ This Month</h3>
            <table class="table table-striped"   >
                <tr>
                    <th>Player</th>
                    <th>2+</th>
                    <th>5+</th>
                    <th>10+</th>
                    <th>15+</th>
                    <th>Total</th>
                </tr>
                @foreach( $completionsThisMonth as $player => $activity )
                    <tr>
                        <td>{{ $player  }}</td>
                        <td>{{ $activity['plus_2']  }}</td>
                        <td>{{ $activity['plus_5']  }}</td>
                        <td>{{ $activity['plus_10']  }}</td>
                        <td>{{ $activity['plus_15']  }}</td>
                        <td>{{ $activity['total']  }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Mythic+ All Time (since 09/09/2018)</h3>
            <table class="table table-striped"   >
                <tr>
                    <th>Player</th>
                    <th>2+</th>
                    <th>5+</th>
                    <th>10+</th>
                    <th>15+</th>
                    <th>Total</th>
                </tr>
                @foreach( $completionsAllTime as $player => $activity )
                    <tr>
                        <td>{{ $player  }}</td>
                        <td>{{ $activity['plus_2']  }}</td>
                        <td>{{ $activity['plus_5']  }}</td>
                        <td>{{ $activity['plus_10']  }}</td>
                        <td>{{ $activity['plus_15']  }}</td>
                        <td>{{ $activity['total']  }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@stop