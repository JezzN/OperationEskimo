@foreach( $news as $item )
    <div class="news-item">
        <h3>{{ $item->discussion->title }} <small>{{ $item->time }}</small></h3>
        <p>{!! $item->renderContent() !!}</p>
    </div>
@endforeach

{{ $news->links() }}