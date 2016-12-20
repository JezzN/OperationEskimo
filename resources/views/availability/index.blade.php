@extends('master')

@section('content')

    <div class="table-responsive">
        <table class="table table-striped"  >
            <tr>
                <th>Player</th>
                <th>Title</th>
                <th>Post Created At</th>
            </tr>
            @foreach( $posts as $post )
                <tr >
                    <td>{{ $post->user->username }}</td>
                    <td><a target="_blank" href="{{ $post->discussion->url() }}">{{ $post->discussion->title }}</a></td>
                    <td>{{ $post->time }}</td>
                </tr>
            @endforeach
        </table>
        {{ $posts->links() }}
    </div>

@stop


