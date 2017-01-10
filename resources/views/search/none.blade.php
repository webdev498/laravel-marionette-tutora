@extends('_.layouts.default', [
    'page_class' => 'page--search'
])


@section('ab')
    <!-- Google Analytics Content Experiment code -->
    <script>function utmx_section(){}function utmx(){}(function(){var
    k='104121290-6',d=document,l=d.location,c=d.cookie;
    if(l.search.indexOf('utm_expid='+k)>0)return;
    function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
    '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
    '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
    valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
    '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
    </script><script>utmx('url','A/B');</script>
    <!-- End of Google Analytics Content Experiment code -->

@stop

@section('body')

    {{-- PAGE HEADER --}}

    <header class="page-head page-head--masthead page-head--form page-head--search">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">Search our amazing tutors.</h1>
                    <p class="subheading zeta">Search and book expert, vetted tutors today. Your first lesson is protected by our 100% Money Back Guarantee.</p>
                </div><!--

                --><form action="{{ route('search.create') }}" method="post" class="[ layout__item ] page-head__aside">
                    <div class="[ layout ]"><!--
                        --><input type="hidden" name="_token" value="{{ csrf_token() }}"><!--

                        --><div class="[ layout__item ] u-mb-">
                          <div class="autocomplete [ js-autocomplete ]" data-autocomplete="subjects">
                              <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject"
                                class="[ input input--full input--large ] [ autocomplete__input ] [ js-autocomplete-input ]">
                          </div>
                        </div><!--

                        --><div class="[ layout__item ]">
                            <input type="text" name="location" placeholder="Location"
                              value="{{ old('location') }}"
                              class="input input--full input--large u-mb-">
                        </div><!--

                        --><div class="[ layout__item ]">
                            <button class="btn btn--full btn--large">Find a tutor</button>
                        </div><!--
                    --></div>
                </form><!--
            --></div>
        </div>
    </header>

    
    <div class="search-header band band--ruled">
        <div class="wrapper">
            <header class="layout"><!--
                --><div class="layout__item search-header__heading">
                    <h4 class="heading">{!!session('message')!!}</h4>
                </div><!--
            --></header>
        </div>
    </div>
   
@stop
