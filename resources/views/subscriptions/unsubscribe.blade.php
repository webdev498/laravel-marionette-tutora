@extends('_.layouts.default', [
    'page_class' => 'page--unsubscribe'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
        </div>
    </header>

    <div class="wrapper">
        <div class="[ layout layout--center ] kb"><!--
            --><div class="[ layout__item ] tac">
                @if ($unsubscribed)
	                <h3>You have successfully unsubscribed</h3>
                @else
                	<h3>Oops, something went wrong. Please try clicking the link again</h3>
                @endif
                <h6><a href="{{ route('home') }}">Go back to the Homepage</a></h6>
            </div><!--

           
        --></div>
    </div>
@stop
