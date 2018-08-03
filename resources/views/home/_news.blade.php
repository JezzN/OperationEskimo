@foreach( $news as $item )
    <div class="news-item" style="margin-bottom: 50px;">
        <h3 style="margin-top: 0;">{{ $item->discussion->title }} <small>{{ $item->time }}</small></h3>
        <p>{!! $item->renderContent() !!}</p>
    </div>
@endforeach

{{ $news->links() }}