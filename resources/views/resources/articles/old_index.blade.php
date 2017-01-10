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
                    <h1 class="heading beta">Blog</h1>
                </div><!--

                --><div class="[ layout__item ] page-head__aside">
                    &nbsp;
                </div><!--
            --></div>
        </div>
    </header>

    <div class="wrapper">
        <div class="[ layout ]"><!--
            @foreach ($posts as $post)
                --><div class="[ layout__item ] post u-mb">
                    <h3>
                        <a href="{{ route('blog.show', [
                            'year'  => $post->year,
                            'month' => $post->month,
                            'day'   => $post->day,
                            'slug'  => $post->slug,
                        ]) }}">
                            {{ $post->title }}
                        </a>
                    </h3>
                    <h5 class="u-mb">{{ implode('/', [$post->day, $post->month, $post->year]) }}</h5>

                    {!! $post->body !!}
                </div><!--
            @endforeach

            --><div class="[ layout__item ]">
                {!! $posts->render() !!}
            </div><!--
        --></div>
    </div>
@stop
