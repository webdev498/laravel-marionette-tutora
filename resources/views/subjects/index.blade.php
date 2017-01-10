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
                    <h1 class="heading beta">Browse Popular Subjects</h1>
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
                    <p class="large u-mt">Tutora connects parents and students with a nationwide network of professional, expert tutors. Our booking process offers a simple way for you to manage and pay for lessons, avoiding the hassle of bringing cash along to lessons. Below you can find a list of the most popular subjects in which we offer tuition. We offer many subjects other than the ones listed below, so if you can't find what you are looking for, try searching for it instead.</p>
                    <p class="large">Oh, and did we mention that we offer a 100% satisfaction guarantee? If you're not entirely happy with your first lesson, we'll pay for your next lesson with a tutor, no questions asked.</p>
                </div>
                <?php $i = 0 ?>
                <div class="[ layout__item ] u-1/4-tablet-and-up ">

                @foreach ($subjects as $subject_group => $subjects)
                   
                    <h3 class="u-mt">
                        {{ str_replace('-', ' ', ucwords($subject_group, '-')) }}
                    </h3>
                    <ul class="[ list-ui list-ui--bare list-ui--v-compact ]">
                        @foreach ($subjects as $subject => $value)
                            
                            <li class="list-ui__item">
                                <a href="{{ route('search.subject', [$subject]) }}">{{ str_unslug($subject) }}</a>
                            </li>
                            

                        @endforeach
                        
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
