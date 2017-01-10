@extends('_.layouts.default', [
  'page_class' => 'page--tutor page--jobs page--job',
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="wrapper u-mt+">
        <div class="layout"><!--
        --><div class="layout__item [ js-tutor-view-job-region ]"></div><!--
    --></div>
    </div>
@stop