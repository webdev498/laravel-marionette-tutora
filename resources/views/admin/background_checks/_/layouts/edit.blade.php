@extends('_.layouts.default', [
    'page_class' => 'page--tutors page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">Tutors / <a href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">{{ $tutor->private->name }}</a></h4>
        </div>
    </div>

    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.personal.show', ['uuid' => $tutor->uuid]) }}"
                           class="[ tabs__link @if (starts_with($route, 'admin.tutors.personal.')) tabs__link--active @endif ]"
                        >
                            Personal
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.billing.index', ['uuid' => $tutor->uuid]) }}"
                           class="[ tabs__link @if ($route === 'admin.tutors.billing.index') tabs__link--active @endif ]"
                        >
                            Billing
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.relationships.index', ['uuid' => $tutor->uuid]) }}"
                          class="[ tabs__link @if ($route === 'admin.tutors.relationships.index') tabs__link--active @endif ]"
                        >
                            Relationships
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.lessons.index', ['uuid' => $tutor->uuid]) }}"
                          class="[ tabs__link @if ($route === 'admin.tutors.lessons.index') tabs__link--active @endif ]"
                        >
                            Lessons
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="wrapper u-mt+">
        <div class="layout"><!--
            --><div class="layout__item">
                @yield('show')
            </div><!--
        --></div>
    </div>
@stop
