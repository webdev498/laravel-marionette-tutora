@extends('_.layouts.default', [
    'page_class' => 'page--tutoring-jobs-listing'
])

@section('body')
    {{-- PAGE HEADER --}}
    <header class="page-head page-head--masthead page-head--form page-head--search">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta tac">
                        Tutor Jobs in {{ ucwords($city) }}
                    </h1>
                    <p class="subheading zeta u-mb- tac">
                        Browse our tutor jobs in {{ ucwords($city) }} below. We have over 1000 jobs posted every month on Tutora, in Maths, English, Science, Spanish, French, and many more subjects, to our team of over 4000 tutors. 
                    </p>
                    <p class="subheading zeta u-mb- tac">
                        Interested in becoming a tutor with Tutora? Sign up using the link below.
                    </p>
                    <div class="tac">
                        <a class="tip btn btn--wide btn--large" href="/become-a-tutor/sign-up">Sign Up As A Tutor</a>
                    </div>
                </div><!--
            --></div>
        </div>
    </header>

    @if ($jobs->meta->count != 0)
        <ul class="list-ui search-results">
            @foreach ($jobs->data as $job)
                <li class="clickable">
                    <a href="/become-a-tutor/sign-up" class="bare" onclick="ga
                    ('send',
                    'event', 'profileView', 'fromSearch', '', 1)">
                        <div class="wrapper">
                            <div class="search-results__body band band--ruled band--large">
                                <div class="layout"><!--
                                    --><div class="layout__item search-results__pic">
                                        <div class="subject--icon subject--{{ $job->subject->iconName }} media__img"></div>
                                    </div><!--

                                    --><div class="layout__item search-results__main">
                                        <header class="search-results__header">
                                            <h4 class="heading u-vam">{{ $job->student->first_name }} {{
                                            $job->student->last_name
                                            }}</h4>
                                        </header>
                                        <h6 class="subheading">{{ $job->subject->title }}</h6>


                                        {!! $job->message !!}

                                    </div><!--

                                    --><div class="layout__item search-results__meta">

                                        <button type="button" class="btn btn--full">Apply</button>

                                    </div><!--
                                --></div>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <div class="wrapper">
            <div class="[ layout ] page-head__body">
                <div class="[ layout__item ] u-mb">
                    <h4>No jobs posted in this area.</h4>
                </div>
            </div>
        </div>
    @endif
@stop
