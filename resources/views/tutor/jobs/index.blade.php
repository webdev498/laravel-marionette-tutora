@extends('_.layouts.default', [
  'page_class' => 'page--tutor page--jobs',
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">@lang('dashboard.tutor.jobs.heading')</h4>
        </div>
    </div>

    @if ( ! Cookie::has('dismissable_tutor_jobs_introduction'))
        <div class="[ band band--ui band--ruled ] [ js-dismissable ]" data-dismissable-id="tutor_jobs_introduction">
            <div class="wrapper">
                <span class="band__close [ icon icon--cancel ] [ js-close ]"></span>
                <strong>@lang('dashboard.tutor.jobs.introduction-title')</strong>
                <p>@lang('dashboard.tutor.jobs.introduction')</p>
            </div>
        </div>
    @endif

    <div class="search-header band band--ruled">
        <div class="wrapper">
            <header class="layout"><!--
                --><div class="layout__item search-header__heading">
                    <h4 class="heading">{{ $results->meta->total }} job(s) near you</h4>
                </div><!--

                --><div class="layout__item search-header__filter">
                    <ul class="list-inline"><!--
                        @foreach ($results->sort_options as $sort)
                                --><li>
                            <a href="{{ $sort->url }}"
                               class="pill pill--small {{ !$sort->active ? 'pill--neutral' : '' }}">
                                {{ $sort->title }}
                            </a>
                        </li><!--
                        @endforeach
                                --></ul>
                </div><!--

                --><div class="layout__item search-header__filter layout__item--filter-by field">
                    <label for="time" class="heading">Filter by...</label>
                    <div class="field__input select select--small select--bordered select--show">
                        <span class="select__placeholder">Filter by...</span>
                        <span class="select__value">{{ $results->activeFilter }}</span>
                        <select name="time" onchange="window.location = this.value;" class="select__field js-filter">
                            @foreach ($results->filter_options as $filter)
                                <option value="{{ $filter->url }}" {{ $filter->active ? 'selected=selected' : '' }}>
                                    {{ $filter->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div><!--

                --><div class="layout__item">
                </div><!--
            --></header>
        </div>
    </div>

    <ul class="list-ui search-results [ js-tutor-jobs-region ]">
        @foreach ($results->jobs as $job)
            <li class="search-results__item" id="item-{{ $job->uuid }}">
                <div class="bare">
                    <div class="wrapper">
                        <div class="search-results__body band band--ruled band--large">
                            <div class="layout">
                                <div class="layout__item search-results__pic media--responsive">
                                    <div class="subject--icon subject--{{ $job->subject->iconName }} media__img"></div>
                                </div><!--
                                --><div class="layout__item search-results__main">
                                    <header class="search-results__header">
                                        <h4 class="heading">{{ $job->student->name }} <small>({{substr($job->location->postcode, 0, -3)}}, {{ $job->distance }} miles away)</small></h4>
                                    </header>

                                    <p><strong>{{ $job->subject->title }}</strong></p>

                                    <p>{{ $job->message }}</p>
                                </div><!--
                                --><div class="layout__item search-results__meta">
                                    <ul class="list-grid list-grid--two">
                                        <li class="list-grid__item">
                                            <div class="favourite {{ $job->tutor->favourite ? 'active' : '' }}">
                                                <span class="icon icon--heart u-vam"></span>
                                                <br>
                                                Favourite
                                            </div>
                                        </li>
                                        <li class="list-grid__item">
                                            <div class="application {{ $job->tutor->applied ? 'active' : '' }}">
                                                <span class="icon icon--fancy-tick u-vam"></span>
                                                <br>
                                                Applied
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="action--button">
                                        @if ($job->tutor->related)
                                            <a href="{{ relroute('tutor.messages.show', ['id' => $job->tutor->relMessageUuid]) }}" type="button" class="btn btn--full">{{ trans('jobs.message_student') }}</a>
                                        @else
                                            <a href="" type="button" class="view_message {{ !$job->tutor->applied ? 'hidden' : '' }} btn btn--full">{{ trans('jobs.view_message') }}</a>
                                            <a href="" type="button" class="message_student {{ $job->tutor->applied ? 'hidden' : '' }} btn btn--full">{{ trans('jobs.message_student') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="wrapper">
        {!! $results->meta->pagination !!}
    </div>
@stop