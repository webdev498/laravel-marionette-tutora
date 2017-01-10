@extends('_.layouts.default', [
    'page_class' => 'page--search'
])

@section('body')
    {{-- PAGE HEADER --}}
    <header class="page-head page-head--masthead page-head--form page-head--search">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">
                        @if( $results->titles->subject)
                            {{$results->titles->subject}} Tutors
                        @else
                            Private Tutors
                        @endif
                        @if ($results->titles->location)
                            in {{ $results->titles->location }}
                        @endif
                    </h1>
                    <p class="subheading zeta u-mb-">
                        @include('_.partials.search.search-heading')
                    </p>

                </div><!--

                --><form action="{{ route('search.create') }}" method="post" class="[ layout__item ] page-head__aside">
                    <div class="[ layout ]"><!--
                        --><input type="hidden" name="_token" value="{{ csrf_token() }}"><!--

                        --><div class="[ layout__item ] u-mb-">
                          <div class="autocomplete [ js-autocomplete ]" data-autocomplete="subjects">
                              <input type="text" name="subject" value="{{ $results->titles->subject ? $results->titles->subject : '' }}" placeholder="Subject"
                                class="[ input input--full input--large ] [ autocomplete__input ] [ js-autocomplete-input ]">
                          </div>
                        </div><!--

                        --><div class="[ layout__item ]">
                            <input @if( ! $results->input->location) title="Please provide your location so we can find tutors near to you" @endif type="text" name="location" placeholder="Postcode"
                              value="{{ $results->input ? $results->input->location : '' }}"
                              class="{{ $results->input->location ? '' : 'tipped' }} input input--full input--large u-mb-">
                        </div><!--

                        --><div class="[ layout__item ]">
                            <button class="tip btn btn--full btn--large">Find a tutor</button>
                        </div><!--
                    --></div>
                </form><!--
            --></div>
        </div>
    </header>

    <div class="search-header band band--ruled">
        <div class="wrapper">
            <header class="layout"><!--
                --><div class="layout__item search-header__heading">
                    <h4 class="heading">{{ $results->meta->total }} results found</h4>
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

                --><div class="layout__item search-header__breadcrumbs">

                    <ul class="list-inline list-inline--breadcrumbs"><!--
                        
                        
                        @foreach ($results->breadcrumbs as $breadcrumb)
                            --><li>
                                <a href="{{ array_get($breadcrumb, 'url') }}">
                                    {{ array_get($breadcrumb, 'title') }}
                                </a>
                            </li><!--
                        @endforeach
                    --></ul>
                </div><!--
            --></header>
        </div>
    </div>


    <ul class="list-ui search-results">
        @foreach ($results->tutors as $tutor)
            <li class="clickable">
                <a href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}" class="bare" onclick="ga('send', 'event', 'profileView', 'fromSearch', '', 1)">
                    <div class="wrapper">
                        <div class="search-results__body band band--ruled band--large">
                            <div class="layout"><!--
                                --><div class="layout__item search-results__pic">
                                    <figure class="profile-pic" >
                                        <img src="/img/profile-pictures/{{ $tutor->uuid }}@180x180.jpg" >
                                    </figure>
                                </div><!--

                                --><div class="layout__item search-results__main">
                                    <header class="search-results__header">
                                        <h4 class="heading u-vam">{{ $tutor->first_name }} {{ $tutor->last_name }}</h4>
                                    </header>
                                        @if ($tutor->profile->ratings_count != 0)
                                            <div class="heading-meta__meta">
                                                <div class="heading-meta__meta">
                                                    <span class="graphic graphic--stars"></span><span class="profile-reviews__rating">{{ $tutor->profile->rating }}/5</span><span> ({{ $tutor->profile->ratings_count }} reviews)</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($tutor->addresses->default->city)
                                        <div class="search-results__location u-mb--">
                                            <span class="icon icon--location u-vam"></span><span class="u-vam u-ml--">{{$tutor->addresses->default->city}} </span>
                                        </div>
                                        @endif
                                        <h6 class="subheading">{{ $tutor->profile->tagline }}</h6>


                                    {!! $tutor->profile->short_bio !!}

                                    <footer class="search-results__footer">
                                        <span>Subjects:</span>
                                        <ul class="inline list-inline list-inline--delimited">
                                            <?php $i=0; ?>
                                            @foreach ($tutor->subjects as $subject)

                                                    @if ($subject->children)
                                                        @foreach ($subject->children as $child)
                                                            <li>
                                                        <span class="">
                                                            {{ $child->name }}
                                                        </span>
                                                                <?php $i++?>
                                                                @if ($child->children)
                                                                    ({{ implode(', ', array_pluck($child->children, 'name'))}})
                                                                @endif

                                                            </li>
                                                            @if ($i >= 3)
                                                                <?php break; ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @if ($i >= 3)
                                                    <?php break; ?>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </footer>
                                </div><!--

                                --><div class="layout__item search-results__meta">
                                    <ul class="list-grid list-grid--two"><!--
                                        --><li class="list-grid__item">
                                            <div class="meta">
                                                <div class="meta__title"><sup>&pound;</sup>{{ $tutor->profile->rate }}</div>
                                                <div class="meta__description">per hour</div>
                                            </div>
                                        </li><!--

                                        --><li class="list-grid__item">
                                            <div class="meta">
                                                <div class="meta__title">
                                                    @if ($tutor->distance)
                                                        {{ $tutor->distance }}
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
                                    <div class="tutor-stats">
                                        @if ($tutor->profile->response_time)
                                            <div class="tutor-stats__stat">
                                                <span class="icon icon--message--active"></span><span>Replies within {{ $tutor->profile->response_time }}</span>
                                            </div>
                                        @endif
                                        @if ($tutor->profile->lessons_count)
                                            <div class="tutor-stats__stat">
                                                <span class="icon icon--book u-vam"></span><span>{{ $tutor->profile->lessons_count }}+ lessons taught</span>
                                            </div>
                                        @endif
                                        @if (in_array($tutor->profile->video_status, ['new', 'edited']))
                                            <div class="tutor-stats__stat">
                                                <span class="icon icon--video u-vam"></span><span>Presentation
                                                    video</span>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn--full">View profile</button>
                                </div><!--
                            --></div>
                        </div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="wrapper">
        {!! $results->meta->pagination !!}
    </div>
    <div class="wrapper">
        <div class="[ layout ] page-head__body">
            <div class="[ layout__item ] u-mb">
                @include('_.partials.search.search-subjects')
            </div>
            <div class="[ layout__item ] u-mb">
                @include('_.partials.search.search-locations')
            </div>

            <div class="[ layout__item ] u-mb">
                @include('_.partials.search.search-about')
            </div>
        </div>
    </div>
@stop
