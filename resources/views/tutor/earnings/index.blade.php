@extends('_.layouts.default', [
  'page_class' => 'page--tutor page--earnings'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="wrapper">
        <div class="layout"><!--
            --><div class="layout__item u-mt u-mb--">
                <h3 class="beta">@lang('dashboard.tutor.earnings.heading')</h3>
                <p class="zeta">@lang('dashboard.tutor.earnings.introduction')</p>
            </div><!--

            --><div class="layout__item u-mt u-mb--">
                <p><strong>Comings soon.</strong></p>
            </div><!--

        --></div>
    </div>
@stop
