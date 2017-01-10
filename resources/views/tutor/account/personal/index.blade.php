@extends('_.layouts.default', [
  'page_class' => 'page--tutor page--account'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <div class="layout"><!--
                --><div class="layout__item">
                    <h4 class="delta u-m0">@lang('dashboard.tutor.account.heading')</h4>
                </div><!--
            --></div>
        </div>
    </div>

    <div class="wrapper [ js-account-region ]"></div>
@stop
