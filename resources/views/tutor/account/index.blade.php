@extends('_.layouts.default', [
  'page_class' => 'page--tutor page--account'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">@lang('dashboard.tutor.account.heading')</h4>
        </div>
    </div>

    @if ( ! Cookie::has('dismissable_tutor_account_introduction'))
        <div class="[ band band--ui band--ruled ] [ js-dismissable ]" data-dismissable-id="tutor_account_introduction">
            <div class="wrapper">
                <span class="band__close [ icon icon--cancel ] [ js-close ]"></span>
                <p>@lang('dashboard.tutor.account.introduction')</p>
            </div>
        </div>
    @endif

    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('tutor.account.personal.index') }}" data-js
                          data-tab-name="personal"
                          class="[ tabs__link ][ js-tab ]"
                        >
                            Personal
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('tutor.account.identification.index') }}" data-js
                          data-tab-name="identification"
                          class="[ tabs__link ][ js-tab ]"
                        >
                            Identification
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('tutor.account.payment.index') }}" data-js
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
        <div class="[ layout layout--rev ]"><!--
            --><div class="layout__item kb__content [ js-account-region ]">
            </div><!--

            --><div class="layout__item kb__links">
                @include('_.partials.profile.requirements')
            </div><!--
        --></div>
    </div>
@stop
