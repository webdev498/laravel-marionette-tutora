<h4>
About {{ $results->titles->subject }} Tuition
@if ($results->titles->location)
    in {{ $results->titles->location }}
@endif</h4>

@if($results->titles->location)
    @if($number_of_reviews > 3)
        <div itemscope="" itemtype="http://schema.org/LocalBusiness">
            Our clients love <span itemprop="name">Tutora</span>'s <span itemprop="address">{{ $results->titles->subject}} Tutors in {{current(explode(",", $results->titles->location))}}</span>. So far <span itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating"><span itemprop="reviewCount">{{ $number_of_reviews }}</span> clients have reviewed our tutors giving an average rating of <span itemprop="ratingValue">{{ round($average_review, 1) }}</span>/<span itemprop="bestRating">5</span>.</span>
            </div>
        <div>
    @endif
    @if(! $results->titles->subject && View::exists(strtolower('_.partials.search.search-footers.'. $results->titles->location)))
        @include(strtolower('_.partials.search.search-footers.' . $results->titles->location))
    @endif
  
    @if( $results->titles->subject && $results->titles->location && View::exists(strtolower('_.partials.search.search-footers.'. $results->titles->subject . '-' . $results->titles->location)))

        @include(strtolower('_.partials.search.search-footers.' . $results->titles->subject . '-' . $results->titles->location))
    @endif

    </div>
@endif