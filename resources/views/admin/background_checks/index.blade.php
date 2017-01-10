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
            <h4 class="delta u-m0" style="display: inline-block">Background checks</h4>
        </div>
    </div>

    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    @foreach ([
                        \App\UserBackgroundCheck::FILTER_PENDING => 'Pending',
                        \App\UserBackgroundCheck::FILTER_EXPIRED => 'Expired',
                    ] as $key => $value)
                        <li class="tabs__item">
                            <a href="{{ relroute('admin.background_checks.index', ['filter' => $key]) }}"
                            class="[ tabs__link @if ($filter === $key) tabs__link--active @endif ]"
                            >
                                {{ $value }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="wrapper u-mt">
        <div class="layout"><!--

            --><div class="layout__item">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tutor</th>
                            <th>Type</th>
                            <th>Date DBS Uploaded</th>
                            <th>Certificate</th>
                            <th>Certificate Number</th>
                            <th>Surname</th>
                            <th>Date of Birth</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($backgroundChecks->meta->count > 0)
                            @foreach ($backgroundChecks->data as $background)
                                <tr>
                                    <!-- Tutor -->
                                    <td>
                                        <a href="{{ route('admin.tutors.show', ['uuid' => $background->user->uuid]) }}">
                                            {{ $background->user->name }}
                                        </a><br>
                                        {{ $background->user->uuid }}
                                    </td>

                                    <!-- Type -->
                                    <td>
                                        {{ $background->type_title }}
                                    </td>

                                    <!-- Date DBS Uploaded -->
                                    <td>
                                        <abbr title="{{ $background->created_at->long }}">
                                            {{ $background->created_at->long }}
                                        </abbr>
                                    </td>

                                    <!-- Certificate -->
                                    <td>
                                        @if ($background->image)
                                            <a href="{{ $background->image->paths->origin }}" target="_blank">&#10004;</a>
                                        @endif
                                    </td>

                                    <!-- Certificate Number -->
                                    <td>
                                        {{ $background->certificate_number }}
                                    </td>

                                    <!-- Surname -->
                                    <td>
                                        {{ $background->last_name }}
                                    </td>

                                    <!-- Date of Birth -->
                                    <td>
                                        {{ $background->dob }}
                                    </td>

                                    <!-- Action -->
                                    <td class="u-vam tar">
                                        <a
                                            href="{{ route('admin.tutors.background_check.index', [
                                                'uuid' => $background->user->uuid,
                                            ]) }}"
                                                >
                                            <span title="Show" class="icon icon--eye"></span>
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="8">
                                No results
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                @if ($backgroundChecks->meta->count > 0)
                    {!! $backgroundChecks->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
