@extends('emails._.layouts.tutor_marketing')

@section('heading')
	Do you have space to take on more students?
@stop

@section('subheading')
	If so, message any of the students below to offer your services. Let them know how, when and where you can help them.
@stop

@section('action')

@stop

@section('details')
	
	@foreach($jobs as $job)
	
		<tr style="padding-left: 15%;padding-right: 15%; font-family: Helvetica, sans-serif; font-size: 16px;">
            <td valign="top" align="center" class="">
                <table border="0" cellspacing="0" cellpadding="0" style="margin: 0; padding:0" width="750px">
                    <tr>
                        <td width="500" align="" valign="center">
							<strong>{{ $job->subject->title }} - {{substr($job->location->postcode, 0, -3)}}, {{ $job->distance }} miles from you</strong>
                            <br>
                            {{ $job->shortMessage }}
                        </td>
                        <td width="" align="center" valign="top">
							<a href="{{ route('tutor.jobs.show', ['uuid' => $job->uuid]) }}" class="btn btn__anchor" style="background-color: #12d4d2; display: inline-block; font-size: 24px; line-height: 60px;text-align: center;text-decoration: none;padding: 0 20px;color:#ffffff !important; text-decoration: none !important;">{{ trans('jobs.emails.apply_job') }}</a>
                        </td>
                    </tr>
                    <tr height=20px></tr>
                </table>
            </td>
        </tr>


	@endforeach
@stop

