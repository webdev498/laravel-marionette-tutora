{!! $tutor->profile->video_status === 'new' || $tutor->profile->video_status === 'edited' ?
'<div class="js-video-wrapper"><div class="js-video-player"></div></div>'
: '' !!}
<a href="{{ relroute('tutor.profile.show', [
                'uuid'    => $tutor->uuid,
                'section' => 'video'
                ]) }}" data-js class="btn btn--small u-ml--">
    {{ $tutor->profile->video_status === 'new' || $tutor->profile->video_status === 'edited' ? 'Edit your Video' : 'Record a Video' }}
</a>