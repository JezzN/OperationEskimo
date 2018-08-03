<nav class="navbar navbar-default">
    <div class="container container-navbar">
    <div class="container-fluid container-navbar-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="https://forums.operation-eskimo.com"><img src="https://forums.operation-eskimo.com/assets/{{$logo}}" style="max-height: 30px; vertical-align: middle;"/></a></li>
                @foreach( $links as $link )
                    <li style="padding-top: 3px;">
                        <a class="nav-link" {{ $link->is_newtab ? 'target="_blank"' : '' }} href="{{ $link->url }}" title="{{ $link->title }}">{{ $link->title }}</a>
                    </li>
                @endforeach
            </ul>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
        </div>
</nav>