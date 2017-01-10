@extends('_.layouts.default', [
    'page_class' => 'page--about'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">About Us</h1>
                </div><!--

                --><div class="[ layout__item ] page-head__aside">
                    <a href="{{ route('about.index') }}#contact-us" class="btn btn--full">Contact us</a>
                </div><!--
            --></div>
        </div>
    </header>

    <div class="wrapper">
        <div class="timeline"><!--
            --><div class="timeline__item">
                <p>
                    Whilst working as a teacher in Sheffield, Scott was often asked by
                    parents of children in his class to recommend good tutors but, after
                    taking a long time to search (and we mean a long time), found it was
                    by no means an easy task! Discussing this with Mark, a Technology Analyst
                    and long-time friend, the two struck upon the simple idea of a website
                    that took the hassle out of his parents’ searches.
                </p>

                <p>
                    The two set about designing and creating a website that brought all of
                    the best tutors to one place, without parents, or indeed any learners,
                    having to pay large sums to tuition agencies, scour through endless
                    message boards or worry about finding the right person for them.
                </p>
            </div><!--
            
            --><div class="timeline__item timeline__item--heading">
                <span class="icon icon--bulb"></span>
                <h2 class="heading gamma">Tutora was born</h2>
            </div><!--
            
            --><div class="timeline__item">
                <p>
                    Our online platform allows you to connect directly with talented and
                    trusted tutors, removes the hassle of dealing in cash, whilst, we believe,
                    providing outstanding customer service. We constantly strive to ensure
                    that everyone can have access to a first class tutor: any subject, any
                    age, we have the right tutor for you.
                </p>

                <a href="{{ route('home') }}">So, let us help you find your perfect tutor</a>
            </div><!--
            
            --><div class="timeline__item">
                <p>
                    Tutora was founded with the idea that there must be a better way for
                    all, and that means tutors as well! Having worked as a tutor himself,
                    Scott lead our team by collating the best resources out there, helping
                    them to manage their diaries and offering first class training to ensure
                    that they are the best tutors they can be.
                </p>
            </div><!--
        --></div>

        <div class="layout"><!--
            --><header class="layout__item header">
                    <h2 class="gamma heading">Our Team</h2>
            </header><!--

            --><div class="layout__item team">
                <div class="layout"><!--
                    --><div class="layout__item team__member">
                        <header>
                            <figure class="tac">
                                <img src="/img/scott@180x180.png" alt="Scott Woddley">
                            </figure>

                            <h3 class="gamma">Scott Woodley</h3>
                            <span class="brand">Co-Founder</span>
                        </header>
                        <p>Scott is a qualified teacher, with a Masters in Law and a BA History degree. An
                            idealist to the core, Scott always seeks to ‘make things better’ and perfect our
                            service: hence our manic workload! He is a keen cyclist, runner and, generally,
                            an avid sports enthusiast.</p>
                    </div><!--z~

                    --><div class="layout__item team__member">
                        <header>
                            <figure class="tac">
                                <img src="/img/mark@180x180.png" alt="Mark Hughes">
                            </figure>
                            <h3 class="gamma">Mark Hughes</h3>
                            <span class="brand">Co-Founder</span>
                        </header>
                        <p>Mark has a Masters and degree in Engineering from Cambridge University and has since
                            worked as a Technology Analyst for one of the largest investment companies in the
                            country. He brings the business mind and technological know-how to our organisation,
                            and, as the creative genius of our team, has an ardent passion for ensuring that
                            Tutora provides a smooth, slick platform for everyone. Mark loves climbing and can
                            normally be found hanging off mountains in the Peak District.</p>
                    </div><!--
                --></div>
            </div><!--

            --><header class="layout__item header">
                    <h2 class="gamma heading" id="contact-us">Contact Us</h2>
            </header><!--
            
            @if (Auth::user() && Auth::user()->isAdmin())

                --><div class="layout__item">
                    <div class="layout contact u-mb-"><!--
                        --><div class="layout__item contact__icon">
                            <span class="icon icon--disc icon--telephone"></span>
                        </div><!--

                        --><div class="layout__item contact__content">
                            <span class="telephone">0114 215 7026</span>
                            <p>Monday - Friday 9.30am - 6.00pm</p>
                        </div><!--
                    --></div>
                </div><!--

            @endif

            --><div class="layout__item">
                <div class="layout contact"><!--
                    --><div class="layout__item contact__icon">
                        <span class="icon icon--disc icon--contact"></span>
                    </div><!--

                    --><div class="layout__item contact__content">
                        <form method="post" action="{{ route('about.contact') }}" novalidate>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="layout"><!--
                                --><div class="layout__item field field--name">
                                    <input type="text" name="name" placeholder="Name"
                                      class="[ input input--full input--squared input--bordered ] field__input">
                                    <div class="field__error"></div>
                                </div><!--

                                --><div class="layout__item field field--email">
                                    <input type="text" name="email" placeholder="Email"
                                      class="[ input input--full input--squared input--bordered ] field__input">
                                    <div class="field__error"></div>
                                </div><!--

                                --><div class="layout__item field field--subject">
                                    <input type="text" name="subject" placeholder="Subject"
                                      class="[ input input--full input--squared input--bordered ] field__input">
                                    <div class="field__error"></div>
                                </div><!--

                                --><div class="layout__item field field--message">
                                    <textarea name="body" placeholder="Message"
                                      class="[ input input--full input--squared input--bordered ] field__textarea"></textarea>
                                    <div class="field__error"></div>
                                </div><!--

                                --><div class="layout__item tar">
                                    <button class="btn">Send message</button>
                                </div><!--
                            --></div>
                        </form>
                    </div><!--
                --></div>
            </div><!--
        --></div>
    </div>
@stop
