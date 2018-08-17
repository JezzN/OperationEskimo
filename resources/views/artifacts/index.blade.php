@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-12">
            <h3>Heart of Azeroth</h3>
            <div class="table-responsive">
                <table class="table table-striped" style="font-size: 1.1em;"  >
                    <tr>
                        <th>Player</th>
                        <th>Item Level</th>
                        <th>Level</th>
                    </tr>
                    @foreach( $artifacts as $artifact )
                        <tr>
                            <td>{{ $artifact->character_name  }}</td>
                            <td>{{ $artifact->character->average_item_level  }}</td>
                            <td>{{ $artifact->level  }}
                                <progress value="{{ $artifact->experience  }}" max="{{ $artifact->experience_remaining }}"></progress>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <h3>Champions of Azeroth</h3>
            <div class="table-responsive">
                <table class="table table-striped" style="font-size: 1.1em;"  >
                    <tr>
                        <th>Player</th>
                        <th>Standing</th>
                        <th>Progress</th>
                    </tr>
                    @foreach( $champions as $rep )
                        <tr>
                            <td>{{ $rep->character_name  }}</td>
                            <td>{{ $rep->getStanding()  }}</td>
                            <td>{{ $rep->value  }} / {{ $rep->max }}
                                <progress value="{{ $rep->value  }}" max="{{ $rep->max }}"></progress>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <h3>The Honorbound</h3>
            <div class="table-responsive">
                <table class="table table-striped" style="font-size: 1.1em;"  >
                    <tr>
                        <th>Player</th>
                        <th>Standing</th>
                        <th>Progress</th>
                    </tr>
                    @foreach( $honorbound as $rep )
                        <tr>
                            <td>{{ $rep->character_name  }}</td>
                            <td>{{ $rep->getStanding()  }}</td>
                            <td>{{ $rep->value  }} / {{ $rep->max }}
                                <progress value="{{ $rep->value  }}" max="{{ $rep->max }}"></progress>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@stop
