@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-10">
            <h3 style="margin-top: 0;">Tanks ({{ count($tanks) }})</h3>
            <div class="characters">
                @foreach( $tanks as $member )
                    @include('roster._character')
                @endforeach
            </div>

            <h3>Healers ({{ count($healers) }})</h3>
            <div class="characters">
                @foreach( $healers as $member )
                    @include('roster._character')
                @endforeach
            </div>

            <h3>Melee DPS ({{ count($meleeDps) }})</h3>
            <div class="characters">
                @foreach( $meleeDps as $member )
                    @include('roster._character')
                @endforeach
            </div>

            <h3>Ranged DPS ({{ count($rangedDps) }})</h3>
            <div class="characters">
                @foreach( $rangedDps as $member )
                    @include('roster._character')
                @endforeach
            </div>

            @unless( $unknown->isEmpty() )
                <h3>Role Not Updated ({{ count($unknown) }})</h3>
                <div class="characters">
                    @foreach( $unknown as $member )
                        @include('roster._character')
                    @endforeach
                </div>
            @endunless
        </div>

        <div class="col-md-2">
            <h3>Ranks</h3>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>Trial</th>
                        <td>{{ $guildRoleCounts['trials'] }}</td>
                    </tr>
                    <tr>
                        <th>Raider</th>
                        <td>{{ $guildRoleCounts['members'] + $guildRoleCounts['officers'] }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>{{ array_sum($guildRoleCounts) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@stop
