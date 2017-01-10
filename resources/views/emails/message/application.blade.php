@extends('emails._.layouts.default')

@section('heading')
    {{$line->getLine()->user->first_name}} can help with your tuition request
@stop

@section('body')
    Hello {{ $user->first_name }},
    <br>
    Great news! {{$line->getLine()->user->first_name}} is able to help with your tuition request. Their message is below. To view their profile, click on the link and you will be able to read about their experience, and previous feedback. 
    <br>
    <br>
    {!! str_replace(['</p><p>', '<p>', '</p>'], ['<br><br>', '', ''], $line->getBody()) !!}<br>
    <br>
    @if ($tutor)
        ~ {{ $tutor->first_name }}

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
                            <td><p class="mediumP" style="
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
    @endif

    <a href="{{ route('unsubscribe', [$user->subscription_token])}}"></a>
@stop

@section('action')
    <a href="{{ route('message.redirect', [
        'uuid' => $line->getLine()->message->uuid,
    ]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Reply to Message</a>
@stop
