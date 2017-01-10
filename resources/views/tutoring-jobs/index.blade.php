@extends('_.layouts.default', [
    'page_class' => 'page--tutoring-jobs-index'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center  ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">Browse Cities for Tutor Jobs</h1>
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
                <p class="large u-mt">Looking for tutoring jobs, but don't know where to start? At Tutora, we have over 1000 students and parents actively seeking tutors right now, in all subjects ranging from English and Maths through to Biomechanics and Statistics. To apply to any of the tuition jobs, you must be a tutor with Tutora. To find out how to become a tutor, click "become a tutor" and see how working with Tutora can help build your tuition business.</p>
                <p class="large">Below is just a small sample of the cities where we offer tutoring jobs. Click on a city to see a sample of the jobs in that local area.</p>
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
                                <a href="{{ route('tutoring.jobs.show', [$city]) }}">{{ ucwords($city) }}</a>
                            </li>


                        @endforeach
                        {{--<li><a href="{{ route('locations.show', ['region' => $region]) }}" class="bare">View all »</a></li>--}}
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
