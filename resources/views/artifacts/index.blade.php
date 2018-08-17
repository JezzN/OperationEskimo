@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-12">
            <h3>Heart of Azeroth</h3>
            <div class="table-responsive">
                <table class="table table-striped" style="font-size: 1.1em;"  >
                    <tr>
                        <th>Player</th>
                        <th>Level</th>
                    </tr>
                    @foreach( $artifacts as $artifact )
                        <tr>
                            <td>{{ $artifact->character_name  }}</td>
                            <td>{{ $artifact->level  }}
                                <progress value="{{ $artifact->experience  }}" max="{{ $artifact->experience_remaining }}"></progress>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@stop
