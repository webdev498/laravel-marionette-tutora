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
                    <h1 class="heading beta">Top Tutoring Cities in {{ str_replace('-', ' ', ucwords($region, '-')) }}</h1>
                </div><!--

                --><div class="[ layout__item ] page-head__aside">
                    &nbsp;
                </div><!--
            --></div>
        </div>
    </header>

    <div class="wrapper">
        <div class="[ layout layout--center] u-mt">
            
                <?php $i = 0 ?>

                
               <div class="[ layout__item ] u-1/4-tablet-and-up">
               <ul class="[ list-ui list-ui--bare list-ui--v-compact ]">
                @foreach ($cities as $city => $value)
                    
                    <?php $i++ ?>

                    <li class="list-ui__item">
                        <a href="{{ route('search.location', [$city]) }}">{{ ucwords($city) }}</a>
                    </li>
                    {{-- {{$i % 4}} --}}
                    @if($i % $break == 0 && $i != 0)
                        </ul>
                        </div><!--   
                        --><div class="[ layout__item ] u-1/4-tablet-and-up">
                        <ul class="[ list-ui list-ui--bare list-ui--v-compact ]">
                    @endif


                @endforeach
                
                </div>

                
                    
           
            

            <div class="[ layout__item ]">
                
            </div><!--
        --></div>
    </div>
@stop
