@extends('emails._.layouts.default')

@section('heading')
    Your background check was rejected
@stop

@section('body')
    Hey {{ $user->first_name }},<br>
    <br>
    Unfortunately we couldn't accept your DBS Check, for the following reason:<br><br>
    @if($background->type == \App\UserBackgroundCheck::TYPE_DBS_CHECK)

    	@if($background->rejected_for == \App\UserBackgroundCheck::DBS_REJECT_REASON_OUT_OF_DATE)
            "Your DBS check is out of date. Unfortunately we can only accept DBS checks issued within the past two years. Do you have a more recent check you could upload?"
        @elseif($background->rejected_for == \App\UserBackgroundCheck::DBS_REJECT_REASON_NO_COLOUR)
            "Your DBS check is not in colour. Unfortunately, to ensure the document is genuine, we can only accept documents in colour. Could you reupload a colour picture?"
        @elseif($background->rejected_for == \App\UserBackgroundCheck::DBS_REJECT_REASON_NOT_CLEAR)
            "Your DBS check was not clear enough. Could you upload a high quality image where the text is clearly legible?"
        @elseif($background->rejected_for == \App\UserBackgroundCheck::DBS_REJECT_REASON_NOT_WHOLE)
            "We could not see the whole DBS check. Please make sure the image you upload contains the entire document."
        @elseif($background->rejected_for == \App\UserBackgroundCheck::DBS_REJECT_REASON_CUSTOM)
            "{{$background->reject_comment}}"
        @endif

    @elseif($background->type == \App\UserBackgroundCheck::TYPE_DBS_UPDATE)
        @if($background->rejected_for == \App\UserBackgroundCheck::DBS_UPDATE_REJECT_REASON_NOT_FOUND)
            "We couldn't find a record matching the details you uploaded. Could you please double check the record and reupload the correct details?"
         @elseif($background->rejected_for == \App\UserBackgroundCheck::DBS_UPDATE_REJECT_REASON_SERVICE_ID)
            "We need the DBS certificate number (rather than the DBS Update Number) to check the record. Could you reupload your DBS with the correct number."
         @endif
    @endif
    <br>
    <br>
    If you wish to upload you check again, please click on the link below. We normally review a new DBS check within 2 business days.
@stop

@section('action')
    <a href="{{ route('tutor.profile.show', ['uuid' => $user->uuid]) }}/background_check" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Re-upload a DBS Check</a>
@stop
