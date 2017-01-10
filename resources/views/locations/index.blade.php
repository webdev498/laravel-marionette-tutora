@extends('_.layouts.default', [
    'page_class' => 'page--locations'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center  ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">Browse Cities and Locations</h1>
                </div><!--

                --><div class="[ layout__item ] page-head__aside">
                    &nbsp;
                </div><!--
            --></div>
        </div>
    </header>

    <div class="wrapper">
        <div class="[ layout layout--center]">
            
                <div class="[ layout__item ] ">
                    <p class="large u-mt">Tutora connects parents and students with a nationwide network of professional, expert tutors. Our simple booking process allows you to schedule and pay for lessons online, avoiding all the hassle of bringing cash along to lessons. Below you can find a list of the most popular cities throughout the UK where we currently offer tuition services. If you can't find your city or town listed, try searching for it directly.</p>
                    <p class="large">Oh, and did we mention that we offer a 100% satisfaction guarantee? If you're not entirely happy with your first lesson, we'll pay for your next lesson with a tutor, no questions asked.</p>
                </div>
                <?php $i = 0 ?>
                <div class="[ layout__item ] u-1/4-tablet-and-up ">

                @foreach ($locations as $region => $cities)
                   
                    <h3 class="u-mt">
                        {{ str_replace('-', ' ', ucwords($region, '-')) }}
                    </h3>
                    <ul class="[ list-ui list-ui--bare list-ui--v-compact ]">
                        @foreach ($cities as $city => $value)
                            
                            <li class="list-ui__item">
                                <a href="{{ route('search.location', [$city]) }}">{{ ucwords($city) }}</a>
                            </li>
                            

                        @endforeach
                        <li><a href="{{ route('locations.show', ['region' => $region]) }}" class="bare">View all »</a></li>
                    </ul>
                    @if($i % 2 == 1)
                        </div><!--   --><div class="[ layout__item ] u-1/4-tablet-and-up">
                    @endif
                    <?php $i++ ?>


                @endforeach
                </div>

                
                    
           
            

            <div class="[ layout__item ]">
                
            </div><!--
        --></div>
    </div>
@stop
