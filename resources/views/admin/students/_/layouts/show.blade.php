@extends('_.layouts.default', [
    'page_class' => 'page--students page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled band--dark @if($student->private->deleted_at) band--danger @endif ]">
        <div class="wrapper">
            <div class="layout"><!--
                    --><div class="layout__item">
                    <div class="tal u-mt- ib">
                        <h4 class="delta inline">Students / {{ $student->private->name }}</h4>
                    </div>
                    <div class="tar r inline">
                        <a data-js="" href="{{ relroute('admin.students.review', ['uuid' => $student->uuid ]) }}" class="btn btn--primary">Leave a review</a>
                        @if($student->private->deleted_at) <span>This student has been deleted</span> @endif
                        @if(! $student->private->deleted_at)

                            <a data-js="" href="{{ relroute('admin.students.delete', ['uuid' => $student->uuid ]) }}" class="btn btn--error">Delete Student</a>
                            @if($student->private->blocked_at)
                                <a data-js="" href="{{ relroute('admin.students.unblock', ['uuid' => $student->uuid ]) }}" class="btn btn--error">Unblock Student</a>
                            @else
                                <a data-js="" href="{{ relroute('admin.students.block', ['uuid' => $student->uuid ]) }}" class="btn btn--error">Block Student</a>
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
                        <a
                          href="{{ relroute('admin.students.personal.show', [
                            'uuid' => $student->uuid
                          ]) }}"
                          class="[
                            tabs__link
                            @if (starts_with($route, 'admin.students.personal.'))
                                tabs__link--active
                            @endif
                          ]"
                        >
                            Personal
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a
                          href="{{ relroute('admin.students.payment.index', [
                            'uuid' => $student->uuid
                          ]) }}"
                          class="[
                            tabs__link
                            @if (starts_with($route, 'admin.students.payment.'))
                                tabs__link--active
                            @endif
                          ]"
                        >
                            Payment
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a
                          href="{{ relroute('admin.students.jobs.index', [
                            'uuid' => $student->uuid
                          ]) }}"
                          class="[
                            tabs__link
                            @if (starts_with($route, 'admin.students.jobs.'))
                                tabs__link--active
                            @endif
                          ]"
                        >
                            Jobs
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a
                          href="{{ relroute('admin.students.relationships.index', [
                            'uuid' => $student->uuid
                          ]) }}"
                          class="[
                            tabs__link
                            @if (starts_with($route, 'admin.students.relationships.'))
                                tabs__link--active
                            @endif
                          ]"
                        >
                            Relationships
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a
                          href="{{ relroute('admin.students.lessons.index', [
                              'uuid' => $student->uuid
                          ]) }}"
                          class="[
                            tabs__link
                            @if (starts_with($route, 'admin.students.lessons.'))
                                tabs__link--active
                            @endif
                          ]"
                        >
                            Lessons
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a
                          href="{{ relroute('admin.students.settings.show', [
                              'uuid' => $student->uuid
                          ]) }}"
                          class="[
                            tabs__link
                            @if (starts_with($route, 'admin.students.settings.'))
                                tabs__link--active
                            @endif
                          ]"
                        >
                            Settings
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
