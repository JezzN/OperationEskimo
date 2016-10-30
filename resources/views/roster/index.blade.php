@extends('master')

@section('content')
    <div class="row">


        <div class="table-responsive">
            <table class="table"  >
                <tr>
                    <th  style="text-align: center">Trial</th>
                    <th  style="text-align: center">Raider</th>
                    <th  style="text-align: center">Total</th>
                </tr>
                <tr>
                    <td  style="text-align: center">{{ $guildRoleCounts['trials'] }}</td>
                    <td  style="text-align: center">{{ $guildRoleCounts['members'] + $guildRoleCounts['officers'] }}</td>
                    <td  style="text-align: center">{{ array_sum($guildRoleCounts) }}</td>
                </tr>
            </table>
        </div>



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
    </div>
@stop
