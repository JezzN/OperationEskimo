<h3>{{ $name }}</h3>
<div class="table-responsive">
    <table class="table table-striped" style="font-size: 1.1em;"  >
        <tr>
            <th>Player</th>
            <th>Standing</th>
            <th>Progress</th>
        </tr>
        @foreach( $reputations as $rep )
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