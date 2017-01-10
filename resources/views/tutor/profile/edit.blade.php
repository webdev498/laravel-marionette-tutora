@extends('_.layouts.tutor', [
    'page_class' => 'page--tutor page--profile'
])

@section('tagline')
    <div class="[ layout ] profile-tagline"><!--
        --><div class="[ layout__item ] profile-tagline__text">
            <h2 class="heading delta">
                <span class="js-tagline">
                    @if ($tutor->profile->tagline)
                        {{ $tutor->profile->tagline }}
                    @else
                        <em class="unset">Click here to set your tagline</em>
                    @endif
                </span>

                <a href="{{ relroute('tutor.profile.show', [
                    'uuid' => $tutor->uuid,
                    'section' => 'tagline'
                ]) }}" data-js class="edit-link u-ml--">Edit</a>
            </h2>
        </div><!--
    --></div>
@stop

@section('rate')
    @parent
    <div>
        <a href="{{ relroute('tutor.profile.show', [
            'uuid'    => $tutor->uuid,
            'section' => 'rate'
        ]) }}" data-js  class="edit-link">Edit</a>
    </div>
@stop

@section('distance')
    <div class="[ js-distance ]">
        <span class="profile-details__title">{{ $tutor->profile->distance }}</span>
        
        <div>
            <a href="{{ relroute('tutor.profile.show', [
                'uuid'    => $tutor->uuid,
                'section' => 'travel_policy'
            ]) }}" data-js class="edit-link">Edit</a>
        </div>
    </div>
@stop

@section('pic')
    <form class="[ js-pic ]">
        <figure class="profile-pic profile-pic--large profile-pic--edit">
            <div class="[ js-click ]">
                <div class="profile-pic__img [ js-img ]">
                    <img src="/img/profile-pictures/{{ $tutor->uuid }}@180x180.jpg">
                </div>

                <div class="profile-pic__edit">
                    <span class="edit-link edit-link--light u-ml--"></span>Edit
                </div>

                <div class="profile-pic__progress [ js-progress ]"></div>

                <div class="profile-pic__preview [ js-preview ]"></div>
            </div>
        </figure>
    </form>
@stop

@section('aside')
    @include('_.partials.profile.requirements')
@stop

@section('bio')
    <header class="profile-body__heading">
        <h4 class="heading">
            {{ $tutor->first_name }} {{ $tutor->last_name }}
            <a href="{{ relroute('tutor.profile.show', [
                'uuid'    => $tutor->uuid,
                'section' => 'bio'
            ]) }}" data-js class="edit-link u-ml--">Edit</a>
        </h4>
    </header>

    <div class="tac js-video u-mt">
        @include('_.partials.profile.video')
    </div>

    <div class="js-bio u-mt-">
        @if ($tutor->addresses->default->city)
                        <div class="search-results__location u-mb-- u-mt--">
                            <span class="icon icon--location u-vam"></span><b><span class="u-vam u-ml--">{{$tutor->addresses->default->city}} </span></b>
                        </div>
                    @endif

        @if ($tutor->profile->bio)
            {!! $tutor->profile->bio !!}
        @else
            <p>You haven&#39;t written a bio yet.</p>
        @endif
    </div>
@stop

@section('badges')
    <div class="[ js-badges ]">
                
       @if ($tutor->background_checks->background_status === 'approved')

            <div class="tutor-stats__stat tutor-stats__stat--large">
            <span class="icon icon--fancy-tick u-vam"></span><span class="text-aligned">I have a Background Check</span>
            </div>
        @endif

        @if ($tutor->qualifications && $tutor->qualifications->qts && $tutor->qualifications->qts->key !== 'no')
            <div class="tutor-stats__stat tutor-stats__stat--large">
            <span class="icon icon--teacher--large u-vam"></span><span class="text-aligned">{{ $tutor->qualifications->qts->title }}</span>
            </div>
            
        @endif

        <a href="{{ relroute('tutor.profile.show', [
            'uuid'    => $tutor->uuid,
            'section' => 'qts'
        ]) }}" data-js class="edit-link">Edit Qualified Teacher Status</a>
        <br>
    </div>
@stop

