@extends('master')

@section('content')
    <div class="table-responsive">
        <table class="table table-striped" style="font-size: 1.1em;"  >
            <tr>
                <th>Player</th>
                <th>Artifact</th>
                <th>Rank</th>
                <th>Artifact Item Level</th>
            </tr>
            @foreach( $artifacts as $artifact )
                <tr style="{{ $artifact->offspec ? 'color: #CCCCCC' : '' }}">
                    <td>{{ $artifact->member->character_name  }}</td>
                    <td>{{ $artifact->artifact_name  }}</td>
                    <td>{{ $artifact->rank  }}</td>
                    <td>{{ $artifact->level  }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@stop
