@extends('_.layouts.default', [
    'page_class' => 'page--jobs page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    @yield('show')
@stop
