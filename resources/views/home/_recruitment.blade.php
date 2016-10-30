<h3>Recruitment</h3>
<table class="table recruitment" >
    @foreach( $recruitment as $position )
        <tr>
            <th style="color: #{{ \App\OE\WoW\ClassColourMap::nameToColour($position['class']) }}">{{ $position['friendly_name'] }}</th>
            <td class="recruitment-{{ $position['status'] }}">{{ $position['status'] }}</td>
        </tr>
    @endforeach
</table>