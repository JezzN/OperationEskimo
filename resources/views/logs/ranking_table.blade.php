<div class="table-responsive">
    <table class="table table-striped"  >
        <tr>
            <th>Date</th>
            <th>Encounter</th>
            <th>Player</th>
            <th>Metric</th>
            <th>Item Level</th>
            <th>Rank</th>
            <th>%</th>
        </tr>
        @foreach( $rankings as $ranking )
            <tr >
                <td>{{ $ranking->fight_start_time }}</td>
                <td>{{ $ranking->encounter }} {{ ucfirst($ranking->difficulty) }}</td>
                <td><a href="{{ route('logs.character', ['character' => $ranking->character_name]) }}">{{ $ranking->character_name }}</a></td>
                <td>{{ strtoupper($ranking->metric) }}</td>
                <td>{{ $ranking->item_level }}</td>
                <td>{{ $ranking->rank }}/{{ $ranking->out_of }}</td>
                <td class="percentile percentile-{{ $ranking->getPercentileBracket() }}">{{ $ranking->adjustedPercentile() }}</td>
            </tr>
        @endforeach
    </table>
    {{ $rankings->links() }}
</div>