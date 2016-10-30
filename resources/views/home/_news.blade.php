@foreach( $news as $item )
    <div class="news-item">
        <h3>{{ $item->discussion->title }}</h3>
        <p>{!! $item->content !!}</p>
    </div>
@endforeach