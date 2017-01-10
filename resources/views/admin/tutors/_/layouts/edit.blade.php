@extends('_.layouts.default', [
    'page_class' => 'page--tutors page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled band--dark @if($tutor->private->deleted_at) band--danger @endif ]">
        <div class="wrapper">
            <div class="layout"><!--
                    --><div class="layout__item">
                    <div class="tal u-mt- ib">
                        <h4 class="delta inline">Tutors / <a href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">{{ $tutor->first_name }} {{ $tutor->private->last_name }}</a></h4>
                    </div>
                    <div class="tar r inline">
                            @if($tutor->private->deleted_at) <span>This tutor has been deleted</span> @endif
                            @if(! $tutor->private->deleted_at)
                                <a data-js="" href="{{ relroute('admin.tutors.delete', ['uuid' => $tutor->uuid ]) }}" class="btn btn--error">Delete Tutor</a>
                                @if($tutor->private->blocked_at)
                                    <a data-js="" href="{{ relroute('admin.tutors.unblock', ['uuid' => $tutor->uuid ]) }}" class="btn btn--error">Unblock Tutor</a>
                                @else
                                    <a data-js="" href="{{ relroute('admin.tutors.block', ['uuid' => $tutor->uuid ]) }}" class="btn btn--error">Block Tutor</a>
                                @endif
                            @endif
                    </div>
                </div><!--
                --></div>
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
                        <a href="{{ relroute('admin.tutors.background_check.index', ['uuid' => $tutor->uuid]) }}"
                           class="[ tabs__link @if ($route === 'admin.tutors.background_check.index') tabs__link--active @endif ]"
                        >
                            Background check
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
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.reviews.index', ['uuid' => $tutor->uuid]) }}"
                           class="[ tabs__link @if ($route === 'admin.tutors.reviews.index') tabs__link--active @endif ]">
                            Reviews
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
