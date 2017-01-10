@extends('_.layouts.default', [
    'page_class' => isset($page_class) ? $page_class : 'page--tutor',
])

<?php $is_editable = isset($is_editable) ? $is_editable : false; ?>



@section('body')
    {{-- PAGE HEADER --}}

    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>
    @if ($user && $user->isAdmin())
        <div class="[ band band--ruled band--dark @if($tutor->private->deleted_at) band--danger @endif ]">
            <div class="wrapper">
                <div class="layout"><!--
                    --><div class="layout__item">
                        <div class="tal u-mt- ib">
                            <h4 class="delta inline">{{ $tutor->first_name }} {{ $tutor->private->last_name }}</h4> -
                            <h5 class="epsilon inline">
                                {{ $tutor->profile->status }},
                                {{ $tutor->profile->admin_status }}
                            </h5>

                        </div>
                        <div class="tar r inline">
                            @if($tutor->private->deleted_at) <span>This tutor has been deleted</span> @endif
                            <a href="{{ route('admin.tutors.show', ['uuid' => $tutor->uuid ]) }}" class="btn">Edit in admin</a>
                            @if(! $tutor->private->deleted_at)<a data-js="" href="{{ relroute('admin.tutors.delete', ['uuid' => $tutor->uuid ]) }}" class="btn btn--error">Delete Tutor</a>@endif
                        </div>
                    </div><!--
                --></div>
            </div>
        </div>
    @endif

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            @section('tagline')
                <div class="[ layout ] profile-tagline"><!--
                    --><div class="[ layout__item ] profile-tagline__text">
                        <h1 class="heading delta">
                            <span class="js-tagline">{{ $tutor->profile->tagline }}</span>
                        </h1>
                    </div><!--

                    --><div class="[ layout__item ] profile-tagline__action">
                        <a href="{{ relroute('message.create', [
                            'uuid' => $tutor->uuid,
                        ]) }}" data-js class="btn btn--full" rel="nofollow" onclick="ga('send', 'event', 'message', 'start', '', 1)">
                            Message Me
                        </a>
                    </div><!--
                --></div>
            @show
        </div>
    </div>



    <div class="wrapper">
        <div class="layout"><!--
            --><div class="layout__item profile-meta">
                <div class="tac">
                    @section('pic')
                        <figure class="profile-pic profile-pic--large">
                            <img src="/img/profile-pictures/{{ $tutor->uuid }}@180x180.jpg">
                        </figure>
                    @show
                </div>

                @section('aside')

                    <header class="u-mt">
                        <h6 class="heading">Contact me</h6>
                    </header>

                    <p>Send me a <a href="{{ relroute('message.create', [
                            'uuid' => $tutor->uuid,
                        ]) }}" data-js>message</a> briefly explaining the subject and time you would like tuition in.</p>
                    <p>I'll reply by email as soon as I can.</p>

                    <a href="{{ relroute('message.create', [
                        'uuid' => $tutor->uuid,
                    ]) }}" data-js class="btn btn--full u-mt-" rel="nofollow" onclick="ga('send', 'event', 'message', 'start', '', 1)">
                        Message Me
                    </a>

                @show
            </div><!--

            --><section class="layout__item profile-body">
                @section('bio')
                    <header class="heading-meta__heading">
                        <h4 class="heading">{{ $tutor->first_name }} {{ $tutor->last_name }}</h4>
                    </header>
                    @if ($tutor->profile->ratings_count > 0)
                        <div class="heading-meta__meta">
                            <a href="#reviews"><span class="profile-reviews__rating">
                                <span class="graphic graphic--stars"></span> {{ $tutor->profile->rating }}
                            </span></a> ({{ $tutor->profile->ratings_count }} reviews)
                        </div>
                    @endif
                    @if ($tutor->addresses->default->city)
                        <div class="search-results__location u-mb-- u-mt--">
                            <span class="icon icon--location u-vam"></span><b><span class="u-vam u-ml--">{{$tutor->addresses->default->city}} </span></b>
                        </div>
                    @endif
                    @if ($tutor->profile->video_status === 'new' || $tutor->profile->video_status === 'edited')
                        <div class="js-video-wrapper">
                            <div class="js-video-player u-mt-"></div>
                        </div>
                    @endif
                    <div class="u-mt-">{!! $tutor->profile->bio !!}</div>
                @show
            </section><!--

            --><aside class="layout__item profile-aside">
                    <ul class="list-grid list-grid--two"><!--
                    --><li class="list-grid__item">
                        <div class="meta">
                        @section('rate')
                            <div class="meta__title">
                                <sup>&pound;</sup><span class="js-rate">{{ $tutor->profile->rate }}</span>
                            </div>
                            <div class="meta__description">per hour</div>
                        @show
                        </div>
                    </li><!--

                    --><li class="list-grid__item">
                        <div class="meta">
                            <div class="meta__title">
                                @if ($tutor->distance !== null)
                                    @section('distance')
                                        {{ $tutor->distance }}
                                    @show
                                @else
                                    <abbr title="Search with a location">
                                        ?
                                    </abbr>
                                @endif
                            </div>
                            <div class="meta__description">miles away</div>

                        </div>
                    </li><!--
                --></ul>
                @if ($tutor->profile->response_time || $tutor->profile->lessons_count || ($tutor->background_check && $tutor->background_check->has_dbs) || ($tutor->qualifications && $tutor->qualifications->qts))
                <div class="tutor-stats box box--neutral">
                    @if ($tutor->profile->response_time)
                        <div class="tutor-stats__stat tutor-stats__stat--large">
                            <span class="icon icon--message--large u-vam"></span><span class="text-aligned">Replies within {{ $tutor->profile->response_time }}</span>
                        </div>
                    @endif
                    @if ($tutor->profile->lessons_count)
                        <div class="tutor-stats__stat tutor-stats__stat--large">
                            <span class="icon icon--book--large u-vam"></span><span class="text-aligned">{{ $tutor->profile->lessons_count }}+ lessons taught</span>
                        </div>
                    @endif
                    @if (in_array($tutor->profile->video_status, ['new', 'edited']))
                        <div class="tutor-stats__stat tutor-stats__stat--large">
                            <span class="icon icon--video--large u-vam"></span><span
                                    class="text-aligned">Presentation video</span>
                        </div>
                    @endif

                    @section('badges')
                        @if ($tutor->background_checks && $tutor->background_checks->background_status === 'approved')

                            <div class="tutor-stats__stat tutor-stats__stat--large">
                            <span class="icon icon--fancy-tick u-vam"></span><span class="text-aligned">I have a Background Check</span>
                            </div>
                        @endif

                        @if ($tutor->qualifications && $tutor->qualifications->qts && $tutor->qualifications->qts->key !== 'no')
                            <div class="tutor-stats__stat tutor-stats__stat--large">
                            <span class="icon icon--teacher--large u-vam"></span><span class="text-aligned">{{ $tutor->qualifications->qts->title }}</span>
                            </div>

                        @endif
                    @show
                </div>
                @endif

                @include('_.partials.profile.subjects')

                @include('_.partials.profile.qualifications')

                @include('_.partials.profile.travel-policy')
            </aside>
            @if($tutor->profile->ratings_count > 0)
            <section class="layout__item profile-reviews" id="reviews">
                <header class="profile-reviews__header layout"><!--
                    --><div class="layout__item heading-meta">
                        <div class="heading-meta__heading">
                            <h4 class="heading">Reviews</h4>
                        </div>
                        <div class="heading-meta__meta">
                            <span class="profile-reviews__rating">
                                <span class="graphic graphic--stars"></span> {{ $tutor->profile->rating }}
                            </span> ({{ $tutor->profile->ratings_count }} reviews)
                        </div>
                    </div><!--
                --></header>

                @if ($tutor->reviews)
                    @foreach ($tutor->reviews as $review)
                        <div class="profile-review">
                            <div class="layout"><!--
                                --><div class="profile-review__meta layout__item">
                                    <div class="flag">
                                        <div class="profile-review__img flag__img">
                                            <figure class="profile-pic">
                                                <img src="/img/profile-pictures/{{ $review->reviewer->uuid }}@80x80.jpg">
                                            </figure>
                                        </div>
                                        <div class="flag__body">
                                            <div class="profile-review__name">{{ $review->reviewer->first_name }} {{ $review->reviewer->last_name }}</div>
                                            <div class="graphic graphic--stars graphic--stars-{{ $review->rating }}"></div>
                                        </div>
                                    </div>
                                </div><!--

                                --><div class="profile-review__body layout__item">
                                    {!! $review->body !!}
                                </div><!--
                            --></div>
                        </div>
                    @endforeach
                @else
                    <div class="profile-review">
                        <div class="layout"><!--
                            --><div class="profile-review__meta layout__item">
                                &nbsp;
                            </div><!--
                            --><div class="profile-review__body layout__item">
                                @if ($is_editable)
                                    <p class="unset">You don’t have any reviews yet. Your students can leave you a review after their lesson.</p>
                                @else
                                    <p class="unset">{{ $tutor->first_name }} hasn't received any reviews yet.</p>
                                @endif
                            </div><!--
                        --></div>
                    </div>
                @endif
            </section>
        @endif
        </div>
    </div>
@stop

@section('scripts-after')
@stop

