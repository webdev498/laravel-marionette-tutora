@extends('_.layouts.default', [
    'page_class' => 'page--faqs'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">Frequently Asked Questions</h1>
                    <h3 class="subheading gamma">For Students</h3>
                </div><!--

                --><div class="[ layout__item ] page-head__aside">
                    <a href="{{ route('home') }}" class="btn btn--full">Find a tutor</a>
                </div><!--
            --></div>
        </div>
    </header>

    <div class="wrapper">
        <div class="[ layout layout--center ] faqs"><!--
            --><div class="[ layout__item ] faqs__links">
                <ul class="[ list-ui list-ui--bare list-ui--compact ]">
                    @foreach (trans('faqs.student') as $faq)
                        <li>
                            <a href="{{ route('faqs.student').'#'.str_slug(array_get($faq, 'heading')) }}">
                                {{ array_get($faq, 'heading') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div><!--

            --><div class="[ layout__item ] faqs__content">
                <dl class="dl">
                    @foreach (trans('faqs.student') as $faq)
                        <dt class="dl__dt" id="{{ str_slug(array_get($faq, 'heading')) }}">
                            {{ array_get($faq, 'heading') }}
                        </dt>
                        <dd class="dl__dd"><p>{!! is_array(($body = array_get($faq, 'body'))) ? implode('</p><p>', $body) : $body !!}</p></dd>
                    @endforeach
                </dl>
            </div><!--
        --></div>
    </div>
@stop
