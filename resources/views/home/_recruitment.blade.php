<div style=" background: #2D2D35; border-radius: 5px 5px 0 0; padding: 10px; text-align: center; margin-bottom:5px;">
    <img src="img/logo_full.png" style="max-height: 100px;">
</div>
<div class="recruitment">
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