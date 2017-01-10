@extends('_.layouts.default', [
    'page_class' => 'page--blog'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">{{ $post->title }}</h1>
                    <h3 class="subheading gamma">{{ implode('/', [$post->day, $post->month, $post->year]) }}</h3>
                </div><!--

                --><div class="[ layout__item ] page-head__aside">
                    &nbsp;
                </div><!--
            --></div>
        </div>
    </header>

    <div class="wrapper">
        <div class="[ layout ]"><!--
            --><div class="[ layout__item ] post">
                {!! $post->body !!}
            </div><!--
        --></div>
    </div>
@stop
