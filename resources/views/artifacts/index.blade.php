@extends('loot.master')

@section('loot-content')
    <div class="row">
        <div class="col-md-6">
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

        <div class="col-md-6">
            <h3>Item Level</h3>
            <div class="table-responsive">
                <table class="table table-striped" style="font-size: 1.1em;"  >
                    <tr>
                        <th>Player</th>
                        <th>Item Level</th>
                    </tr>
                    @foreach( $raiders as $raider )
                        <tr>
                            <td>{{ $raider->character_name  }}</td>
                            <td>{{ $raider->average_item_level  }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            @include('loot._reputation', ['name' => 'Champions of Azeroth', 'reputations' => $champions])
        </div>

        <div class="col-md-6">
            @include('loot._reputation', ['name' => 'The Honorbound', 'reputations' => $honorbound])
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            @include('loot._reputation', ['name' => 'Voldunai', 'reputations' => $voldunai])
        </div>

        <div class="col-md-6">
            @include('loot._reputation', ['name' => 'Zandalari Empire', 'reputations' => $zandalari])
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            @include('loot._reputation', ['name' => 'Talanji\'s Expedition', 'reputations' => $talanjis])
        </div>

        <div class="col-md-6">
            @include('loot._reputation', ['name' => 'Tortollan Seekers', 'reputations' => $tortollan])
        </div>
    </div>

@stop
