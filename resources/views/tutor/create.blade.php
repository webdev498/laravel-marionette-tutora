@extends('_.layouts.default', [
    'page_class' => 'page--become-a-tutor'
])

@section('body')
    <header class="page-head page-head--masthead page-head--tutor">
        <div class="wrapper">
            @include('_.partials.site-nav.default.student')

            <div class="wrapper">
                <div class="[ layout layout--center ] page-head__body"><!--
                    --><div class="[ layout__item ] page-head__top">
                        <h1 class="heading alpha">
                            <span class="wb">Build your business</span>
                            <span class="wb">with Tutora</span>
                        </h1>
                    </div><!--

                    --><div class="[ layout__item ]">
                        <div class="[ layout layout--center layout--rev ] page-head__bottom"><!--
                            --><div class="layout__item page-head__bottom__top">
                                <p class="zeta">Whether you are new to tutoring or an established professional, we can help you to grow and manage your tuition business.</p>
                            </div><!--

                            --><div class="layout__item page-head__bottom__bottom">
                                <a href="{{ relroute('register.tutor') }}" data-js class="btn btn--full btn--large">Sign Up</a>
                            </div><!--
                        --></div>
                    </div><!--
                --></div>
            </div>
        </div>
    </header>

    <div class="box box--dark promo-blocks">
        <div class="wrapper">
            <div class="layout u-mt+"><!--

                --><div class="layout__item promo-block promo-block--heading">
                    <h2 class="heading">Why Join the Tutora team?</h2>
                </div><!--

                --><div class="layout__item promo-block">
                    <div class="block block--responsive">
                        <div class="block__img">
                            <div class="graphic graphic--graph"></div>
                        </div>
                        <div class="block__body">
                            <header>
                                <h3 class="heading">Grow your Business.</h3>
                            </header>

                            <p class="intro delta">
                                Advertise to thousands of students who are actively seeking tutors.
                            </p>

                            <p class="large">
                                With a free profile and us advertising on your behalf, we will help you 
                                secure new students without investing loads of time and money into marketing.  
                            </p>
                        </div>
                    </div>
                </div><!--

                --><div class="layout__item promo-block">
                    <div class="block block--responsive">
                        <div class="block__img">
                            <div class="graphic graphic--till"></div>
                        </div>
                        <div class="block__body">
                            <header>
                                <h3 class="heading">Get Paid.</h3>
                            </header>

                            <p class="intro delta">
                                Earn &pound;15-30 an hour teaching the subject you love. 
                            </p>

                            <p class="large">
                                You have complete control over the rate you charge, and you can teach as little or as often as
                                you like.  We handle all of the boring invoicing and payments, and you get paid straight
                                into your bank account.  
                            </p>
                        </div>
                    </div>
                </div><!--

                --><div class="layout__item promo-block">
                    <div class="block block--responsive">
                        <div class="block__img">
                            <div class="graphic graphic--ring"></div>
                        </div>
                        <div class="block__body">
                            <header>
                                <h3 class="heading">Great Support.</h3>
                            </header>

                            <p class="intro delta">
                                You focus on teaching - we take care of the rest.
                            </p>

                            <p class="large">
                                Our online tools make managing your business a breeze.  And whether you need help setting
                                up your profile, or are simply looking for advice on securing more students, we’re only
                                a phone call away.
                            </p>
                        </div>
                    </div>
                </div><!--

            --></div>
        </div>
    </div>

    <div class="wrapper">
        <div class="layout layout--center"><!--
            --><div class="layout__item vs-container">
                <div class="vs">
                    <div class="vs__item vs__item--best">
                        <header class="vs__section vs__section--ruled vs__section--header tac">
                            <div class="graphic graphic--logo--dark"></div>
                        </header>

                        <div class="vs__section vs__section--ruled vs__section--point tac">
                            <div class="gamma">15-25%</div>
                            <div class="zeta u--mt-">commission</div>
                        </div>

                        <div class="vs__section vs__section--meta">
                            <ul class="list-pros-cons">
                                <li class="list__item list__item--pro">Choose your own hours</li>
                                <li class="list__item list__item--pro">Select your own pupils</li>
                                <li class="list__item list__item--pro">Larger audience</li>
                                <li class="list__item list__item--pro">Simple <a href="{{ relroute('register.tutor') }}" data-js>Sign up</a></li>
                            </ul>

                            <a href="{{ relroute('register.tutor') }}" data-js class="btn btn--alt btn--full u-mt-">Sign up</a>
                        </div>
                    </div>
                    <div class="vs__item">
                        <header class="vs__section vs__section--ruled vs__section--header tac">
                            <h3 class="heading">Typical Agency</h3>
                        </header>

                        <div class="vs__section vs__section--ruled vs__section--point tac">
                            <div class="gamma">40%+</div>
                            <div class="zeta u--mt-">commission</div>
                        </div>

                        <div class="vs__section vs__section--meta">
                            <ul class="list-pros-cons">
                                <li class="list__item list__item--con">Hours selected for you</li>
                                <li class="list__item list__item--con">Teach the pupils they send</li>
                                <li class="list__item list__item--con">Complicated on-boarding process</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!--
        --></div>
    </div>

    <div class="wrapper">
        <div class="layout layout--rev"><!--
            --><div class="layout__item quotes">
                <section class="quotes__tutors">
                    <header>
                        <h2 class="heading">What Our Tutors Think</h2>
                    </header>

                    <blockquote class="blockquote">
                        <div class="blockquote__body">
                            <p>Creating a profile was really simple and I immediately started receiving
                            requests for tuition. The team are incredibly helpful and supportive.</p>
                        </div>
                        <cite class="blockquote__cite">John P (Music)</cite>
                    </blockquote>

                    <blockquote class="blockquote">
                        <div class="blockquote__body">
                            <p>The administrative support let me focus entirely on my teaching and
                            provide a much better service to my students.</p>
                        </div>
                        <cite class="blockquote__cite">Bethany G (English)</cite>
                    </blockquote>
                </section>

                <seciton class="quotes__tutora">
                    <header>
                        <h2 class="heading">Designed for tutors, by tutors</h2>
                    </header>

                    <blockquote class="blockquote">
                        <div class="blockquote__body">
                            <p>Having worked as a Tutor for many years, I sat down with other tutors to design a
                            system which helps you manage all of your business, message students and arrange
                            sessions with ease. Tutora lets you focus on teaching.</p>
                        </div>
                        {{-- <cite class="blockquote__cite">John P, Music</cite> --}}
                    </blockquote>
                </section>
            </div><!--

            --><section class="layout__item faqs">
                <header>
                    <h2 class="heading">FAQs</h2>
                </header>

                <ul class="list-bare">
                    @foreach (trans('faqs.tutor') as $faq)
                        <li>
                            <p class="large">
                                <a href="{{ route('faqs.tutor').'#'.str_slug(array_get($faq, 'heading')) }}">{{ array_get($faq, 'heading') }}</a>
                            </p>
                        </li>
                    @endforeach
                </ul>

                <p class="large u-mt"><a href="{{ route('faqs.tutor') }}">See all FAQs</a></p>
            </section><!--
        --></div>
    </div>
@stop
