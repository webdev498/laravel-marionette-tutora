@extends('_.layouts.default', [
  'page_class' => 'page--tutor page--account'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ] u-mb">
        <div class="wrapper">
            <div class="layout"><!--
                --><div class="layout__item">
                    <h4 class="delta u-m0">@lang('dashboard.tutor.account.heading')</h4>
                </div><!--
            --></div>
        </div>
    </div>

    <div class="wrapper"
        <div class="[ layout ] kb"><!--
            --><div class="[ layout__item ] kb__links">
                <ul class="[ list-ui list-ui--bare list-ui--compact ]">
                    <li>
                        <a href="{{ route('tutor.account.personal.index') }}">
                            Personal Details
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tutor.account.payment.index') }}">
                            Payment Details
                        </a>
                    </li>
                    <li>
                        <span class="kb__link kb__link--active">ID</span>
                    </li>
                </ul>
            </div><!--

            --><div class="[ layout__item ] kb__content">
                <h4 class="delta">ID</h4>
            </div><!--
        --></div>
    </div>

    <img src="/img/identification/{{ $user->uuid }}.jpg">
@stop
