@foreach( $news as $item )
    <div class="news-item">
        <h3>{{ $item->discussion->title }} <small>{{ $item->time }}</small></h3>
        <p>{!! $item->content !!}</p>
    </div>
@endforeach

{{ $news->links() }}