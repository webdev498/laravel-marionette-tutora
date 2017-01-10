@extends('_.layouts.default', [
    'page_class' => 'page--student page--account'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">@lang('dashboard.student.account.heading')</h4>
        </div>
    </div>

    @if ( ! Cookie::has('dismissable_student_account_introduction'))
        <div class="[ band band--ui band--ruled ] [ js-dismissable ]" data-dismissable-id="student_account_introduction">
            <div class="wrapper">
                <span class="band__close [ icon icon--cancel ] [ js-close ]"></span>
                <p>@lang('dashboard.student.account.introduction')</p>
            </div>
        </div>
    @endif

    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('student.account.personal.index') }}" data-js
                          data-tab-name="personal"
                          class="[ tabs__link ][ js-tab ]"
                        >
                            Personal
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('student.account.payment.index') }}" data-js
                          data-tab-name="payment"
                          class="[ tabs__link ][ js-tab ]"
                        >
                            Payment
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="wrapper u-mt+">
        <div class="[ layout ]"><!--
            --><div class="layout__item u-2/3-tablet-and-up [ js-account-region ]">
            </div><!--
        --></div>
    </div>
@stop
