@extends('logs.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-12">
            <h3>Average Ranking per Player (Mythic only)</h3>
            <p>The average ranking for mythic parses over the past week.</p>
            <div class="table-responsive">
                <table class="table table-striped rankings"  >
                    <tr>
                        <th>Player</th>
                        <th>Type</th>
                        <th>Logs Included</th>
                        <th>Average</th>
                    </tr>
                    @foreach( $averages as $average )
                        @if( ($average->metric === 'dps' && in_array($average->character_name, $dps))
                        || ($average->metric === 'hps' && in_array($average->character_name, $healers)) )
                        <tr >
                            <td>{{ $average->character_name }}</td>
                            <td>{{ $average->metric }}</td>
                            <td>{{ $average->log_count }}</td>
                            <td>{{ round($average->average) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </table>
            </div>

        </div>
    </div>
@stop
