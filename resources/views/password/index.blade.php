@extends('_.layouts.default', [
    'page_class' => 'page--password'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">Lost Password</h1>
                </div><!--

                --><div class="[ layout__item ] page-head__aside">
                    <a href="{{ route('home') }}" class="btn btn--full">Find a tutor</a>
                </div><!--
            --></div>
        </div>
    </header>

    <div class="wrapper">
        <div class="[ layout layout--center ] password"><!--
            --><div class="[ layout__item ] password__body">
                <div class="[ box box--bordered ] u-mt">
                    <p>Enter your email address below and we'll send you a link to reset your password.</p>

                    <p>Remember to check your spam folder if the email doesn’t appear
                        within a few minutes.</p>

                    <form method="post" action="{{ route('password.create') }}" novalidate class="u-mt">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <input type="email" name="email" placeholder="Email"
                          class="[ input input--full input--squared input--bordered ]">

                        <div class="tar u-mt-">
                            <button class="btn">Send reset</button>
                        </div>
                    </form>
                </div>
            </div><!--
        --></div>
    </div>
@stop
