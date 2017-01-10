@extends('_.layouts.default', [
    'page_class' => 'page--dashboard page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">Dashboard</h4>
        </div>
    </div>


@stop

