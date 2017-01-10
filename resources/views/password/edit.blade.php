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
                    <h1 class="heading beta">Reset Your Password</h1>
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
                    <p>Please enter your email address and a new password below.</p>

                    <form method="post" action="{{ route('password.update') }}" novalidate class="u-mt-">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="field">
                            <input type="email" name="email" placeholder="Email"
                              class="[ input input--full input--squared input--bordered ] field__input">
                        </div>

                        <div class="field">
                            <input type="password" name="password" placeholder="New Password"
                              class="[ input input--full input--squared input--bordered ] field__input">
                        </div>

                        <div class="tar">
                            <button class="btn">Reset password</button>
                        </div>
                    </form>
                </div>
            </div><!--
        --></div>
    </div>
@stop
