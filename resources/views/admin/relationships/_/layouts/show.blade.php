@extends('_.layouts.default', [
    'page_class' => 'page--relationships page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="message-head">
        <div class="[ band band--ruled ]">
            <div class="wrapper">
                <h4 class="delta u-m0">
                    Relationships / 
                    <a href="{{ route('admin.tutors.show', ['uuid' => $relationship->tutor->uuid]) }}">
                        {{ $relationship->tutor->private->name }}
                    </a>
                    &amp;
                    <a href="{{ route('admin.students.show', ['uuid' => $relationship->student->uuid]) }}">
                        {{ $relationship->student->private->name }}
                    </a>
            </div>
        </div>

        <div class="[ band band--ruled band--flush ]">
            <div class="wrapper">
                <div class="[ tabs tabs--full ]">
                    <ul class="tabs__list">
                        <li class="tabs__item">
                            <a
                            href="{{ relroute('admin.relationships.details.show', [
                                'id' => $relationship->id
                            ]) }}"
                            class="[
                                tabs__link
                                @if (starts_with($route, 'admin.relationships.details'))
                                    tabs__link--active
                                @endif
                            ]"
                            >
                                Details
                            </a>
                        </li>
                        <li class="tabs__item">
                            <a
                            href="{{ relroute('admin.relationships.messages.show', [
                                'id' => $relationship->id
                            ]) }}"
                            class="[
                                tabs__link
                                @if (starts_with($route, 'admin.relationships.messages.'))
                                    tabs__link--active
                                @endif
                            ]"
                            >
                                Messages
                            </a>
                        </li>
                        <li class="tabs__item">
                            <a
                            href="{{ relroute('admin.relationships.lessons.index', [
                                'id' => $relationship->id
                            ]) }}"
                            class="[
                                tabs__link
                                @if (starts_with($route, 'admin.relationships.lessons.'))
                                    tabs__link--active
                                @endif
                            ]"
                            >
                                Lessons
                            </a>
                        </li>

                    </ul>
                </div>
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

