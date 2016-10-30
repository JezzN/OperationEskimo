<div class="recruitment">
    <h3>Recruitment</h3>
    @if( !empty($recruitment) )
        <table class="table recruitment" >
            @foreach( $recruitment as $position )
                <tr>
                    <th style="color: #{{ \App\OE\WoW\ClassColourMap::nameToColour($position['class']) }}">{{ $position['friendly_name'] }}</th>
                    <td class="recruitment-{{ $position['status'] }}">{{ $position['status'] }}</td>
                </tr>
            @endforeach
        </table>
    @else
        We are not current looking for any particular classes but are always interested in exceptional applicants.
    @endif
    <p>Please complete our <a href="{{ config('operation-eskimo.recruitment-template') }}">application template</a> then post on the <a href="{{ config('operation-eskimo.recruitment-link') }}">recruitment forum</a>.</p>
</div>