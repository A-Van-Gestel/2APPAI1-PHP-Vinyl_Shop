@extends('layouts.template')

@section('main')
    <h3 class="text-center my-5">404 | <span class="text-black-50">Not Found</span></h3>
    @include('shared.error_page_buttons')
@endsection

@section('script_after')
    <script>
        // Remove the right navigation
        $('nav .ml-auto').hide();
    </script>
@endsection
