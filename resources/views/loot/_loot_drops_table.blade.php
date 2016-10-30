<div class="table-responsive">
    <table class="table table-striped" style="font-size: 1.1em;"  >
        <tr>
            <th>Date</th>
            <th>Player</th>
            <th>Type</th>
            <th>Item Level</th>
            <th>Item</th>
        </tr>
        @foreach( $drops as $item )
            <tr>
                <td>{{ $item->loot_time  }}</td>
                <td><a href="{{ route('loot.character', [$item->character_name]) }}">{{ $item->character_name }}</a></td>
                <td>{{ $item->tooltip_description }}</td>
                <td>{{ $item->item_level }}</td>
                <td>{!!  $item->tooltip() !!}</td>
            </tr>
        @endforeach
    </table>
    {{ $drops->links() }}
</div>