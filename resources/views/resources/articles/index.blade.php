@extends('_.layouts.default', [
    'page_class' => 'page--blog'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout  ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">The Tutora Blog</h1>
                </div><!--
            --></div>
        </div>
    </header>

            @foreach ($articles->data as $article)
                <div class="clickable">
                    <a href="{{ route('articles.show', ['slug' => $article->slug]) }}" class="bare">
                    <div class="wrapper">
                        <div class="[ layout ]">
                            <div class="[ layout__item layout__item--main ] preview band band--ruled">                                
                                <div class=" preview__body">
                                    <h3>{{ $article->title }}</h3>
                                    <div class="author">
                                        <figure class="profile-pic profile-pic--small" ><img src="/img/profile-pictures/{{ $article->user->uuid }}@180x180.jpg" ></figure>
                                    
                                        <span>By {{ $article->user->name }}, {{$article->published_at->short}}</span>
                                    </div>
                                    
                                    <p>{!!$article->preview!!}...</p>
                                    
                                </div><!--
                                --><div class="preview__aside">
                                        {!! $article->image !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            @endforeach
            <div class="wrapper">
                <div class="[ layout ]">
                    @if ($articles->meta->count > 0)
                        {!! $articles->meta->pagination !!}
                    @endif
                </div>
            </div>
    
            
        
    </div>
@stop