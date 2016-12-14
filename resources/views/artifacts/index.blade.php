@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-12">
            <h3>Artifact Rankings</h3>
            <div class="table-responsive">
                <table class="table table-striped" style="font-size: 1.1em;"  >
                    <tr>
                        <th>Player</th>
                        <th>Spec</th>
                        <th>Rank</th>
                        <th>Artifact Item Level</th>
                    </tr>
                    @foreach( $artifacts as $artifact )
                        <tr style="color: #{{ $artifact->getColour() }}">
                            <td>{{ $artifact->member->character_name  }}</td>
                            <td>{{ $artifact->getSpec()  }}</td>
                            <td>{{ $artifact->rank  }}</td>
                            <td>{{ $artifact->level  }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@stop
