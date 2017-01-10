@extends('emails._.layouts.marketing')

@section('heading')
    @if ($search->subject)
	    Still looking for a {{ $search->subject->title }} tutor?
    @elseif($search->city)
	    Still looking for a tutor in {{ $search->city }}?
    @endif
@stop

@section('subheading')
   @if($search->city)
   		These are the three most popular tutors in {{$search->city}}...so what are you waiting for?
   	@else
   		These are the three most popular tutors on Tutora...so what are you waiting for? 
   	@endif

@stop

@section('action')
    <a href="{{ route('home', ['utm_source' => 'email-marketing', 'utm_medium' => 'email', 'utm_campaign' => 'marketing-with-suggestions']) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Find a Tutor Now!</a>
@stop

@section('details')
	
	@foreach($tutors as $tutor)
	
		<tr style="padding-left: 15%;padding-right: 15%"">
            <td valign="top" align="center" class="">
                <table border="0" cellspacing="0" cellpadding="0" style="margin: 0px; padding:0px" width="600px">
                    <tr>
                        <td width="180px" align="center" valign="top">
                        	<a href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid, 'utm_source' => 'email-marketing', 'utm_medium' => 'email', 'utm_campaign' => 'marketing-with-suggestions'])}}">
	                        	<img class="profile-picture" style="border-radius: 50%; -moz-border-radius: 50%; -webkit-border-radius: 50%;"src="{{asset("img/profile-pictures/$tutor->uuid@180x180.jpg")}}" >
	                        </a>
                        </td>
                        <td style="padding-left: 20px;" width="420px" align="center"  valign="top">
	                        <table>
		                        <tr>
		                        	<td><a style="color: #12D4D2  !important;
									    font-size: 22px; font-family: helvetica" href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid])}}">{{ $tutor->name }}</a><span style="color: #6b7888  !important;
									    font-size: 20px;"> (£{{$tutor->profile->rate}} / hour)</span>
									</td>
		                        </tr>

		                        <tr>
		                        	<td><p class="mediumP" style=" color: #232F49  !important;
									    font-size: 20px;">{{$tutor->profile->tagline}}</p></td>
		                        </tr>
		                        <tr>
		                        	<td><p>{{$tutor->profile->vshort_bio}}</p></td>
		                        </tr>
	                        </table>
                        </td>
                    </tr>
                    <tr height=20px></tr>
                </table>
            </td>
        </tr>


	@endforeach
@stop

