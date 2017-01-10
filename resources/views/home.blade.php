@extends('_.layouts.default', [
    'page_class' => 'page--home'
])

@section('body')

    {{-- PAGE HEADER --}}

    <header class="page-head page-head--masthead page-head--form page-head--student">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading alpha">
                        <span class="wb">Private tutors for</span>
                        <span class="wb">home tuition.</span>
                    </h1>
                    <h2 class="subheading beta">
                        <span class="wb">Over 3000 Expert Tutors</span>
                        <span class="wb">From £15/hr</span>
                    </h2>
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

                        --><div class="[ layout__item ] u-mb-">
                          <input type="text" name="location" placeholder="Postcode"
                            value="{{ old('location') }}" 
                            class="input input--full input--large">
                        </div><!--

                        --><div class="[ layout__item ]">
                            <button class="btn btn--full btn--large" onclick="ga('send', 'event', 'search', 'start', '', 1);">Find a tutor</button>
                        </div><!--
                    --></div>
                </form><!--
            --></div>
        </div>
    </header>

    {{-- WHY --}}

    <div class="band band--large band--ruled">
        <div class="wrapper">
            <header class="tac">
                <h2 class="heading u-mb+">Why Tutora?</h2>
            </header>

            <div class="media-cascade layout"><!--
                
                --><div class="layout__item media-cascade__item media-cascade__item--odd u-mb+">
                    <div class="media media--large media--rev media--responsive">
                        <div class="media__img">
                            <div class="graphic graphic--binoculars"></div>
                        </div>

                        <div class="media__body">
                            <h3 class="gamma">Hand-Picked Tutors</h3>

                            <p class="large">It can be hard to find great tutors - so we’ve found them for you. We rigorously
                                vet every applicant, and select only the best and brightest to join our team.
                                From Primary through to GCSEs and A-Levels, you can be confident we’ve got the
                                best tutors out there.</p>
                        </div>
                    </div>
                </div><!--

                --><div class="layout__item media-cascade__item media-cascade__item--even u-mb+">
                    <div class="media media--large media--responsive">
                        <div class="media__img">
                            <div class="graphic graphic--seal"></div>
                        </div>

                        <div class="media__body">
                            <h3 class="gamma">Recommended by Others</h3>

                            <p class="large">Read feedback from other parents and students in your area,
                                and message as many tutors as you like to ensure you make the right choice.
                                Once you’re happy, simply ask for a lesson to be scheduled and our tutors 
                                will take care of the rest.</p>
                        </div>
                    </div>
                </div><!--

                --><div class="layout__item media-cascade__item media-cascade__item--odd">
                    <div class="media media--large media--rev media--responsive">
                        <div class="media__img">
                            <div class="graphic graphic--certificate"></div>
                        </div>

                        <div class="media__body">
                            <h3 class="gamma">Proven Results</h3>

                            <p class="large">One-to-one tuition is proven to be the best method of learning
                                and our tutees make exceptional progress. Speak to our tutors about your
                                personal learning goals and let them plan your success.</p>
                        </div>
                    </div>
                </div><!--
            --></div>
        </div>
    </div>

    {{-- MONEY BACK GUARANTEE --}}

    <div class="band band--large band--ruled">
        <div class="wrapper">
            <div class="feature">
                <div class="feature__img">
                    <div class="graphic graphic--shield"></div>
                </div>

                <header class="feature__header">
                    <h3>100% Satisfaction Guarantee</h3>
                </header>

                <div class="feature__body">
                    <p class="large">
                        We do everything we can to ensure a good tutoring match, but if you're not 100% satisfied then we'll pay for your first lesson with another tutor - no questions asked!
                    </p>

                    <p class="large"><a href="#">Search for a Tutor.</a></p>
                </div>
            </div>
        </div>
    </div>

    {{-- EVERYONE LOVES US --}}


    <div class="band">
        <div class="wrapper">
            <header class="tac">
                <h2 class="heading u-mb+ u-mt+">Parents and Tutors Love Us</h2>
            </header>

            <blockquote class="blockquote blockquote--feature">
                <div class="blockquote__body">
                    <p>Tutora helped me find a tutor straight away and I started learning that week.
                        Bethany was professional, enthusiastic and a brilliant teacher!</p>
                </div>

                <div class="blockquote__meta">
                    <span class="graphic graphic--stars graphic--stars-5"></span>
                </div>

                <div class="blockquote__cite">
                    Chris H, Sheffield.
                </div>
            </blockquote>
        </div>
    </div>

    {{-- REAL PEOPLE --}}

    <div class="band band--large band--ruled real-people relative">
        <div class="wrapper tac">
            <div class="founder-pic founder-pic--left"></div>
            <div class="founder-pic founder-pic--right"></div>
            <div class="relative">
                <h3 class="beta u-mb0">Real People</h3>
                <h5 class="gamma">Here to help</h5>
                <a href="{{ route('about.index') }}">About Us</a>
            </div>
        </div>
    </div>

    {{-- POPULAR SEARCHES --}}


    <div class="band">
        <div class="wrapper feature">
            <header class="tac">
                <h2 class="heading u-mb+ u-mt+">Popular Searches On Tutora</h2>
            </header>
            <div class="[ layout ] feature__body feature__body--wide">
                
                <div class="[ layout__item ] col-1/3">
                    <ul class="list-ui list-ui--large">
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'maths', 'location' => 'london']) }}">Maths Tutors London</a>
                        </li>
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'english', 'location' => 'london']) }}">English Tutors London</a>
                        </li>
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'physics', 'location' => 'london']) }}">Physics Tutors London</a>
                        </li>
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'biology', 'location' => 'london']) }}">Biology Tutors London</a>
                        </li>
                    </ul>
                </div><!--
                --><div class="[ layout__item ] col-1/3">
                    <ul class="list-ui  list-ui--large">
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'maths', 'location' => 'sheffield']) }}">Maths Tutors Sheffield</a>
                        </li>
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'english', 'location' => 'sheffield']) }}">English Tutors Sheffield</a>
                        </li>
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'maths', 'location' => 'leeds']) }}">Maths Tutors Leeds</a>
                        </li>
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'english', 'location' => 'leeds']) }}">English Tutors Leeds</a>
                        </li>
                    </ul>
                </div><!--
                --><div class="[ layout__item ] col-1/3">
                    <ul class="list-ui list-ui--large">
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'maths', 'location' => 'manchester']) }}">Maths Tutors Manchester</a>
                        </li>
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'english', 'location' => 'manchester']) }}">English Tutors Manchester</a>
                        </li>
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'maths', 'location' => 'birmingham']) }}">Maths Tutors Birmingham</a>
                        </li>
                        <li class="list-ui__item">
                            <a class="bare" href="{{ route('search.index', ['subject' => 'english', 'location' => 'birmingham']) }}">English Tutors Birmingham</a>
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>

    <div class="band band--huge">
        <div class="wrapper">
            <div class="layout feature-blocks feature-blocks--two"><!--
                --><div class="layout__item feature-blocks__block">
                    <div class="box box--large box--dark">
                        <header class="tac u-mb">
                            <h3 class="heading">Become a Tutor</h3>
                        </header>

                        <ul class="list-checked u-mb+">
                            <li>Advertise for free to gain new students.</li>
                            <li>Continued support and payment processing.</li>
                            <li>Manage your business with our dashboard.</li>
                        </ul>

                        <div class="tac">
                            <a href="{{ relroute('register.tutor') }}"
                              class="btn btn--large" data-js>
                                Sign up
                            </a>
                        </div>
                    </div>
                </div><!--

                --><div class="layout__item feature-blocks__block">
                    <div class="box box--large box--dark">
                        <header class="tac u-mb">
                            <h3 class="heading">Become a Student</h3>
                        </header>

                        <ul class="list-checked u-mb+">
                            <li>Hundreds of DBS checked Tutors.</li>
                            <li>All tutors rigorously vetted.</li>
                            <li>100% satisfaction guarantee.</li>
                        </ul>

                        <div class="tac">
                            <a href="{{ relroute('register.student') }}"
                              class="btn btn--large" data-js>
                                Sign up
                            </a>
                        </div>
                    </div>
                </div><!--
            --></div>
        </div>
    </div>

    
@stop
