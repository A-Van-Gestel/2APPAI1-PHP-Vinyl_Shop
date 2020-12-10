@extends('layouts.template')

@section('title', 'Shop')

@section('css_after')
@endsection


@section('main')
    <h1>iTunes {{ $rss_feed->feed->title }} - {{ strtoupper($rss_feed->feed->country) }}</h1>

    <div class="row">
        @foreach($rss_feed->feed->results as $song)
            <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                <div class="h-100">
                    <div class="card h-100 shadow" data-id="{{ $song->id }}">
                        <img class="card-img-top" src="/assets/vinyl.png" data-src="{{ $song->artworkUrl100 }}" alt="{{ $song->artistName }} - {{ $song->name }}">
                        <div class="card-body h-100">
                            <h5 class="card-title">{{ $song->artistName }}</h5>
                            <p class="card-subtitle mb-2 text-muted">{{ $song->name }}</p>
                            <hr>
                            <p class="text-muted mb-0">Genre: <span class="font-weight-bold">{{ $song->genres[0]->name }}</span></p>
                            <p class="text-muted">Artist URL: <a href="{{ $song->artistUrl }}">{{ $song->artistName }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <p>Last updated: {{ Carbon\Carbon::parse($rss_feed->feed->updated)->toFormattedDateString() }}</p>
@endsection


@section('script_after')
    <script>
        $(function () {
            // Replace vinyl.png with real cover
            $('.card img').each(function () {
                $(this).attr('src', $(this).data('src'));
            });
        })
    </script>
@endsection
