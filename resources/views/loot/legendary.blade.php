@extends('loot.master')

@section('loot-content')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <div class="row">
        <div class="col-md-12">
            <h3>Legendary Drops</h3>
            @include('loot._loot_drops_table')
        </div>
    </div>
    <h3>Legendaries Looted per Week</h3>
    <div id="legendaries" style="height: 250px; margin-bottom: 50px;"></div>

    <script>
        new Morris.Line({
            element: 'legendaries',
            data: {!!  json_encode($stats)!!},
            xkey: 'week',
            ykeys: ['count'],
            labels: ['Legendaries Looted'],
            parseTime: false,
            hideHover: true
        });
    </script>
@stop

